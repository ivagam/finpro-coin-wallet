<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class BurnController extends Controller
{
    public function burn()
    {
        return view('burn.index');
    }
    public function storeBurn(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        $token = session('token');
        $user  = session('user');

       

        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->post("{$apiBase}/api/burn", [
                'address' => $request->address,
                'amount' => $request->amount
            ]);

            if ($response->failed()) {
                return redirect()->back()->with('error', 'Burn request failed: ' . $response->body());
            }

            $data = $response->json();

            if (($data['status'] ?? '') === 'ok') {
                return redirect()->back()->with('success', 'Burn successful!');
            } else {
                return redirect()->back()->with('error', $data['error'] ?? 'Unknown error');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function ajaxBurn(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        $token = session('token');
        $user  = session('user');

        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->post("{$apiBase}/api/burn", [
                'address' => $request->address,
                'amount' => $request->amount
            ]);

            if ($response->failed()) {
                $body = $response->body();

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $body,
                        'error' => $body
                    ], 500);
                }

                return redirect()->back()->with('error', 'Burn request 11failed: ' . $body);
            }

            $data = $response->json();

            if (($data['status'] ?? '') === 'ok') {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Burn successful!'
                    ]);
                }
                return redirect()->back()->with('success', 'Burn successful!');
            } else {
                $errMsg = $data['error'] ?? 'Unknown error';
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $errMsg
                    ], 400);
                }
                return redirect()->back()->with('error', $errMsg);
            }
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Server error: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function burnReport(Request $request)
    {
        $token = session('token');

       
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/api/reports/burn");

            if ($response->failed()) {
                return redirect()->back()->with('error', 'Failed to fetch burn report: ' . $response->body());
            }
            
            $data = $response->json();
            $burnReport = $data['data'] ?? [];

            return view('burn.burnReport', ['burnReport' => $burnReport]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
}