<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    public function transfer()
    { 
        return view('transfer.index');
    }

    public function storeTransfer(Request $request)
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
            $response = Http::withToken($token)->post("{$apiBase}/api/transfer", [
                'address' => $request->address,
                'amount' => $request->amount
            ]);

            if ($response->failed()) {
                return redirect()->back()->with('error', 'Transfer request failed: ' . $response->body());
            }

            $data = $response->json();

            if (($data['status'] ?? '') === 'ok') {
                return redirect()->back()->with('success', 'Transfer successful!');
            } else {
                return redirect()->back()->with('error', $data['error'] ?? 'Unknown error');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
    
    public function transferHistory(Request $request)
    {
        $token = session('token');

        if (!$token) {
            return redirect('/login')->with('error', 'Session expired, please login again.');
        }

        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/api/transactions");

            if ($response->failed()) {
                return redirect()->back()->with('error', 'Failed to fetch transfer report: ' . $response->body());
            }
            
            $data = $response->json();
            $transactions = $data['data'] ?? [];
            
            $perPage = 10;
            $page = (int) $request->get('page', 1);
            $collection = collect($transactions);
            $paginatedItems = $collection->slice(($page - 1) * $perPage, $perPage)->values();

            $paginated = new LengthAwarePaginator(
                $paginatedItems,                
                $collection->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );

            return view('transfer.transferReport', [
                'transactions' => $paginated
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
}
