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
        ])->post('https://api.openai.com/v1/engines/text-davinci-003/completions', [
                'prompt' => 'Can you provide a compelling story or narrative related to the product ' . $request->input('product_name') . ' with a catchy title.',
                // 'language' => $request->input('language'),
                'temperature' => $request->input('temperature'),
                'max_tokens' => (int) $request->input('max_tokens'),
                'n' => 5,                
                'stop' => '\n',
                'top_p' => (int) $request->input('top_p'),
                'frequency_penalty' => (int) $request->input('frequency_penalty'),
                'presence_penalty' => (int) $request->input('presence_penalty')            
            ]);

        $productDescriptionResponse = Http::withHeaders([                                                 
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY')
        ])->post('https://api.openai.com/v1/engines/text-davinci-003/completions', [
                'prompt' => 'Generate an Amazon product listing for a ' . $request->input('product_name') . '.Provide the key features, specifications, and benefits of the product.',
                // 'language' => $request->input('language'),
                'temperature' => $request->input('temperature'),
                'max_tokens' => (int) $request->input('max_tokens'),
                'n' => (int) $request->input('n'),
                'stop' => '\n' ,
                'top_p' => (int) $request->input('top_p'),
                'frequency_penalty' => (int) $request->input('frequency_penalty'),
                'presence_penalty' => (int) $request->input('presence_penalty')               
            ]);

        return response()->json([
            'product_names' => $productNameResponse->json(),
            'product_descriptions' => $productDescriptionResponse->json(),
        ]);
    }

}