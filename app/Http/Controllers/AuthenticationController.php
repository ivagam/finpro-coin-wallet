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
    public function register(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255|min:5',
            'email'    => 'required|email|max:255',
            'password'    => 'required|string|max:30|min:8',
        ]);

        $apiBase = rtrim(env('NODE_API_URL'), '/');

        $response = Http::post("{$apiBase}/api/register", [
            'fullname' => $request->input('fullname'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        $data = $response->json();

        if ($response->successful()) {
            return back()->with('success', 'We have sent verification email to your email address');
        } else {
            return back()->with('error', $data['message'] ?? 'registration failed');
        }
    }

    public function sendPassword(Request $request)
    {
         $request->validate([
            'email'    => 'required|email|max:255',
        ]);
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        $response = Http::post("{$apiBase}/api/forgotpassword", [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['token'])) {
            return back()->with('success', 'we have sent password to your email address');
        } else {
            return back()->with('error', $data['message'] ?? 'Email does not exist.');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('signin');
    }
}
