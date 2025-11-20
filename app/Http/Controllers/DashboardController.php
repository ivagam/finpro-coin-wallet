<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $token = session('token');
        $user  = session('user');
        $apiBase = rtrim(env('NODE_API_URL'), '/');
        $response = Http::withToken($token)->get("{$apiBase}/api/get-dashboard");

        $data = $response->json();
        $data['cuser'] = $user['id'];
        $data['is_admin'] = $user['is_admin'];

        return view('dashboard.index', $data);
    }  
    public function userList(Request $request)
    {
        $token = session('token');      
        $user = session('user');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/api/reports/userlist");

            
            $data = $response->json();
            $transactions = $data['data'] ?? [];
            
            return view('dashboard.userlist', [
                'users' => $transactions,'is_admin'=>$user['is_admin'],'apiBase'=>$apiBase
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
    
    public function sendTokens(Request $request)
    {
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        $request->validate([
            'amount'   => 'required|numeric|min:0.00000001',
            'address'  => 'required|string|max:255',
            'type'     => 'required|in:send,burn'
        ]);

        try {
            $response = Http::withToken($token)->post("$apiBase/api/wallet/send", [
                'amount'  => $request->amount,
                'address' => $request->address,
                'type'    => $request->type
            ]);

            $data = $response->json();

            if ($response->failed()) {
                return back()->with('error', $data['message'] ?? 'Transaction failed');
            }

            return back()->with('success', $data['message']);

        } catch (\Exception $e) {
            return back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function withdraw(Request $request)
    {
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        $request->validate([
            'amount' => 'required|numeric|min:0.00000001'
        ]);

        try {

            $response = Http::withToken($token)->post("$apiBase/api/wallet/withdraw", [
                'amount' => $request->amount
            ]);

            $data = $response->json();

            if ($response->failed()) {
                return back()->with('error', $data['message'] ?? 'Withdrawal failed');
            }

            return back()->with('success', $data['message']);

        } catch (\Exception $e) {

            return back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }


}
