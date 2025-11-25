<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


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
        return view('authentication.signup');
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
          
            // regenerate session id
            $request->session()->regenerate();

            return redirect('/dashboard');
        } else {
            return redirect()->route('login', ['error'=>$data['message'] ?? 'Invalid credentials.']);
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
        ]);

        $data = $response->json();

        if ($response->successful() && $data['status'] == 'ok') {
            return back()->with('success', 'we have sent password to your email address');
        } else {
            return back()->with('error', $data['message'] ?? 'Email does not exist.');
        }
    }
    public function verifyEmail(Request $request)
    {
         
        $verification_code = $request->input('token');
        $error = "Invalid Access";
        $success = "";
        if($verification_code == ''){
                        return redirect('/');
        }
        $apiBase = rtrim(env('NODE_API_URL'), '/');
        $response = Http::get("{$apiBase}/api/verify-email", [
            'verification_code' => $request->input('token')
        ]);
        $data = $response->json();
        if ($response->successful() && $data['status'] == 'ok') {
            $success = 'yes';
        } else {
            $error = "Invalid verification code or expired code";
        }
       

         return view('authentication.verifyemail', [
                'error' => $error,'success'=>$success,
            ]);
    }


    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/');
    }
}
