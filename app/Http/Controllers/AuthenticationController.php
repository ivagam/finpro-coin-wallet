<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthenticationController extends Controller
{
    public function signin()
    {
        return view('authentication.signin');
    }

    public function forgotPassword()
    {
        return view('authentication.forgotPassword');
    }

    public function signUp()
    {
        return view('authentication.signUp');
    }

    public function login(Request $request)
    {
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        $response = Http::post("{$apiBase}/api/login", [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['token'])) {
            session(['token' => $data['token'], 'user' => $data['user']]);
            return redirect('/dashboard');
        } else {
            return back()->with('error', $data['message'] ?? 'Invalid credentials.');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('signin');
    }
}
