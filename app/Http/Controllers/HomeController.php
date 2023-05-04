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
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY')
        ])->post('https://api.openai.com/v1/engines/davinci/completions', [
            'prompt' => 'Generate an Amazon product listing for a ' . $request->input('product_name') . ' with the following description: ' . $request->input('product_description'),
            'temperature' => $request->input('temperature'),
            'max_tokens' => $request->input('max_tokens'),
            'n' => $request->input('n'),
            'stop' => $request->input('stop'),
        ]);

        return response()->json($response->json());
    }

}
