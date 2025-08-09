<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function generate_listing(Request $request)
    {
        $productName = $request->input('product_name');
        $productDescription = $request->input('product_description');
        $lengthLimit = $request->input('length_limit', 500); // match JS key
        $descriptionLength = $request->input('description_length', 1); // match JS key

        $prompt = "
        You are an Amazon listing generator.
        Given the product name and description below, respond in strict JSON format:
        {
            \"product_names\": [\"Title 1\", \"Title 2\", \"Title 3\"],
            \"product_description\": \"Detailed description here\"
        }

        Product Name: {$productName}
        Product Description: {$productDescription}
        Max Title Length: {$lengthLimit} bytes
        Description Paragraphs: {$descriptionLength}
        ";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'meta-llama/llama-3.1-8b-instruct',
            'max_tokens' => $lengthLimit,
            'temperature' => 0.7,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that only outputs valid JSON.'],
                ['role' => 'user', 'content' => $prompt]
            ]
        ]);

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '';
        $jsonOutput = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'error' => 'Invalid AI response',
                'raw' => $content
            ], 500);
        }

        return response()->json($jsonOutput);
    }
}
