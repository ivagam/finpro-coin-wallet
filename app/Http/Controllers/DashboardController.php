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

        if (!$token || !$user) {
            return redirect('/login')->with('error', 'Session expired, please login again.');
        }

        $apiBase = rtrim(env('NODE_API_URL'), '/');
        $response = Http::withToken($token)->get("{$apiBase}/api/balances/{$user['id']}");

        if ($response->failed()) {
            return redirect('/login')->with('error', 'Failed to fetch balances from API.');
        }

        $data = $response->json();

        $balances = $data['balances']['balances'] ?? $data['balances'] ?? [];

        $totalBalance = 0;
        foreach ($balances as $bal) {
            $totalBalance += floatval($bal['balance']);
        }

        return view('dashboard.index', [
            'user' => $user,
            'balances' => $balances,
            'totalBalance' => $totalBalance,
        ]);
    }  
    
}
