<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class MintController extends Controller
{
    public function mint()
    {
        return view('mint.index');
    }

    public function storeMint(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        $token = session('token');
        $user  = session('user');

        if (!$token || !$user) {
            return redirect('/login')->with('error', 'Session expired, please login again.');
        }

        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->post("{$apiBase}/api/mint", [
                'address' => $request->address,
                'amount' => $request->amount                
            ]);

            if ($response->failed()) {
                return redirect()->route('mint', ['error'=>'Mint request failed: ' . $response->body()]);

            }

            $data = $response->json();

            if (($data['status'] ?? '') === 'ok') {
                return redirect()->route('mint', ['success'=>'Mint successful!']);
            } else {
                return redirect()->route('mint', ['error'=>$data['error'] ?? 'Unknown error']);
            }
        } catch (\Exception $e) {
            return redirect()->route('mint', ['error'=>'Server error: ' . $e->getMessage()]);
        }
    }

    public function mintReport(Request $request)
    {
        $token = session('token');

        if (!$token) {
            return redirect('/login')->with('error', 'Session expired, please login again.');
        }

        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/api/reports/mint");

            if ($response->failed()) {
                return redirect()->back()->with('error', 'Failed to fetch mint report: ' . $response->body());
            }

            $data = $response->json();
            $mintReport = $data['data'] ?? [];

            return view('mint.mintReport', ['mintReport' => $mintReport]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
}
