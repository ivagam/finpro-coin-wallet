<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;



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
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->post("{$apiBase}/api/user-transfer", [
                'address' => $request->address,
                'amount' => $request->amount
            ]);

            if ($response->failed()) {
                return redirect()->route('transfer', ['error'=>'Transfer request failed: ' . $response->body()]);
            }

            $data = $response->json();

            if (($data['status'] ?? '') === 'ok') {
                return redirect()->route('transfer', ['success'=>'Transfer successful!']);
            } else {
                return redirect()->route('transfer', ['error'=>$data['message'] ?? 'Invalid credentials.']);

            }
        } catch (\Exception $e) {
            return redirect()->route('transfer', ['error'=>'Server error: ' . $e->getMessage()]);

        }
    }

    public function ajaxSend(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->post("{$apiBase}/api/user-transfer", [
                'address' => $request->address,
                'amount'  => $request->amount,
            ]);

            if ($response->failed()) {
                $body = $response->body();
                Log::error('Transfer API failed', ['body' => $body, 'status' => $response->status()]);
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $body,
                        'error' => $body
                    ], max(400, $response->status()));
                }
                return redirect()->back()->with('error', 'Transfer request failed: ' . $body);
            }

            $data = $response->json();

            if (($data['status'] ?? '') === 'ok') {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['status' => 'ok', 'message' => 'Transfer successful!']);
                }
                return redirect()->back()->with('success', 'Transfer successful!');
            }

            // Non-ok payload
            $errMsg = $data['error'] ?? $data['message'] ?? 'Unknown error';
            Log::warning('Transfer API returned non-ok payload', ['payload' => $data]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $errMsg], 400);
            }
            return redirect()->back()->with('error', $errMsg);

        } catch (RequestException $e) {
            Log::error('HTTP request exception on transfer', [
                'message' => $e->getMessage(),
                'response' => $e->response ? $e->response->body() : null,
            ]);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'External API request failed', 'error' => $e->getMessage()], 502);
            }
            return redirect()->back()->with('error', 'External API request failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Exception in storeTransfer', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Server error', 'error' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function ajaxWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->post("{$apiBase}/api/save-withdrawal", [
                'amount'  => $request->amount,
            ]);

            if ($response->failed()) {
                $body = $response->body();
                Log::error('Transfer API failed', ['body' => $body, 'status' => $response->status()]);
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $body,
                        'error' => $body
                    ], max(400, $response->status()));
                }
                return redirect()->back()->with('error', 'Transfer request failed: ' . $body);
            }

            $data = $response->json();

            if (($data['status'] ?? '') === 'ok') {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['status' => 'ok', 'message' => 'Withdrawal successful!']);
                }
                return redirect()->back()->with('success', 'Withdrawal successful!');
            }

            // Non-ok payload
            $errMsg = $data['error'] ?? $data['message'] ?? 'Unknown error';
            Log::warning('Transfer API returned non-ok payload', ['payload' => $data]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $errMsg], 400);
            }
            return redirect()->back()->with('error', $errMsg);

        } catch (RequestException $e) {
            Log::error('HTTP request exception on transfer', [
                'message' => $e->getMessage(),
                'response' => $e->response ? $e->response->body() : null,
            ]);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'External API request failed', 'error' => $e->getMessage()], 502);
            }
            return redirect()->back()->with('error', 'External API request failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Exception in storeTransfer', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Server error', 'error' => $e->getMessage()], 500);
            }
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
                'transactions' => $transactions,'is_admin'=>$user['is_admin'],'apiBase'=>$apiBase
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
                'amount' => 'required|numeric',
            ]);
         } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                    ->withErrors($e->validator)
                    ->withInput() // ensures old() is available
                    ->with('error', 'pills-change-passwork');
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
                return redirect()->back()->withErrors("Node API error: HTTP {$status} — " . substr($body,0,1000));
            } else {
                Log::error('Node API update-profile request failed (no response)', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors('Node API request failed: ' . $e->getMessage());
            }
        }

        // success path
        $transactions = $data['data'] ?? [];
           
        $status = $response->getStatusCode();
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        $headers = $response->getHeaders();
        Log::info("Node update-profile response", ['status'=>$status, 'headers'=>$headers, 'body'=>substr($body,0,2000)]);

        if ($status >= 200 && $data['status'] == 'ok') {
            return redirect()->back()->with('success', 'Deposit saved successfully.');
        }else{
            return redirect()->back()->with('error', $data['message']);
        }

    }
    
    public function approveWithdraw(Request $request)
    {
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        $response = Http::withToken($token)
            ->post("{$apiBase}/api/approve-withdrawal", [
                "withdrawal_id" => $request->withdrawal_id
            ]);

        return $response->json();
    }

    public function approveDeposit(Request $request)
    {
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        $response = Http::withToken($token)
            ->post("{$apiBase}/api/approve-deposit", [
                "deposit_id" => $request->deposit_id
            ]);

        return $response->json();
    }

}
