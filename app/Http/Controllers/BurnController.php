<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
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

        if (!$token || !$user) {
            return redirect('/login')->with('error', 'Session expired, please login again.');
        }

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

    public function burnReport(Request $request)
    {
        $token = session('token');

        if (!$token) {
            return redirect('/login')->with('error', 'Session expired, please login again.');
        }

        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/api/reports/burn");

            if ($response->failed()) {
                return redirect()->back()->with('error', 'Failed to fetch burn report: ' . $response->body());
            }
            
            $data = $response->json();
            $burnReport = $data['data'] ?? [];

            // Manual pagination
            $perPage = 10;
            $page = (int) $request->get('page', 1);
            $collection = collect($burnReport);
            $paginatedItems = $collection->slice(($page - 1) * $perPage, $perPage)->values();

            $paginated = new LengthAwarePaginator(
                $paginatedItems,
                $collection->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );

            return view('burn.burnReport', ['burnReport' => $paginated]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
}