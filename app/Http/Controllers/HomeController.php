<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function generate_listing(Request $request)
    {
        $productNameResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY')
        ])->post('https://api.openai.com/v1/engines/davinci/completions', [
                'prompt' => $request->input('product-name'),
                'temperature' => $request->input('temperature'),
                'max_tokens' => $request->input('max_tokens'),
                'n' => 5,
                'stop' => '\n'
            ]);

        $productDescriptionResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY')
        ])->post('https://api.openai.com/v1/engines/davinci/completions', [
                'prompt' => $request->input('product-description'),
                'temperature' => $request->input('temperature'),
                'max_tokens' => $request->input('max_tokens'),
                'n' => 5,
                'stop' => '\n'
            ]);

        return response()->json([
            'product_names' => $productNameResponse->json(),
            'product_descriptions' => $productDescriptionResponse->json(),
        ]);
    }


}