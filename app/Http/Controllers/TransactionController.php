<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    public function transfer()
    { 
        $token = session('token');
        $user  = session('user');
        $apiBase = rtrim(env('NODE_API_URL'), '/');
        $response = Http::withToken($token)->get("{$apiBase}/api/balances/".$user['id']);

        $data = $response->json();
        $data['cuser'] = $user['id'];
        $data['is_admin'] = $user['is_admin'];

        return view('transfer.index',$data);
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
            $response = Http::withToken($token)->post("{$apiBase}/api/user-transfer", [
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
            
            return view('transfer.transferReport', [
                'transactions' => $transactions
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
    public function withdrawalReport(Request $request)
    {
        $token = session('token');
        $user = session('user');
        
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/api/reports/withdrawal");

            
            $data = $response->json();
            $transactions = $data['data'] ?? [];
            
            return view('transfer.withdrawal', [
                'transactions' => $transactions,'is_admin'=>$user['is_admin']
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function depositReport(Request $request)
    {
        $token = session('token');      
        $user = session('user');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/api/reports/deposit");

            
            $data = $response->json();
            $transactions = $data['data'] ?? [];
            
            return view('transfer.deposit', [
                'transactions' => $transactions,'is_admin'=>$user['is_admin']
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
     // Accept form submit and forward to Node API
    public function saveDeposit(Request $request)
    {
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        // minimal validation for required non-file fields
        try{
            $request->validate([
                'amount' => 'required|string|max:255',
            ]);
         } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                    ->withErrors($e->validator)
                    ->withInput() // ensures old() is available
                    ->with('active_tab', 'pills-change-passwork');
        }

        // Build multipart array
        $multipart = [
            ['name' => 'amount', 'contents' => $request->input('amount')],
        ];

        // files (only attach if provided)
        $fileFields = [
            'attachment' => $request->file('attachment'),
        ];
        foreach ($fileFields as $field => $file) {
            if ($file && $file->isValid()) {
                $multipart[] = [
                    'name'     => $field,
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ];
            }
        }

        // Use Guzzle client
        $client = new Client([
            'timeout' => 60,
            //'verify' => false, // if you hit SSL issues in dev (not recommended)
        ]);

        try {
            $response = $client->request('POST', "{$apiBase}/api/save-deposit", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                    // Do NOT set Content-Type here; Guzzle will set the correct multipart boundary
                ],
                'multipart' => $multipart,
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // If server returns a response (4xx/5xx), it can be inside the exception
            $resp = $e->getResponse();
            if ($resp) {
                $status = $resp->getStatusCode();
                $body = (string)$resp->getBody();
                $headers = $resp->getHeaders();
                Log::error("Node API update-profile error (exception). Status: {$status}", ['headers'=>$headers, 'body'=>$body]);
                return redirect()->back()->withErrors("Node API error: HTTP {$status} — " . substr($body,0,1000))->with('active_tab','pills-edit-profile-tab');
            } else {
                Log::error('Node API update-profile request failed (no response)', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors('Node API request failed: ' . $e->getMessage())->with('active_tab','pills-edit-profile-tab');
            }
        }

        // success path
        $status = $response->getStatusCode();
        $body = (string)$response->getBody();
        $headers = $response->getHeaders();
        Log::info("Node update-profile response", ['status'=>$status, 'headers'=>$headers, 'body'=>substr($body,0,2000)]);

        if ($status >= 200 && $status < 300) {
            return redirect()->back()->with('success', 'Deposit request saved successfully.')->with('active_tab','pills-edit-profile-tab');
        }

        // If we reach here, return useful error
        return redirect()->back()->withErrors("Node API returned HTTP {$status}: " . substr($body,0,1000))->with('active_tab','pills-edit-profile-tab');
    }
    
    public function approveWithdraw(Request $request)
    {
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        $response = Http::withToken($token)
            ->post("{$apiBase}/api/withdraw/approve", [
                "withdrawal_id" => $request->withdrawal_id
            ]);

        return $response->json();
    }
}
