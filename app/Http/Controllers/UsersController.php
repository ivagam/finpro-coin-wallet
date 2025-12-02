<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class UsersController extends Controller
{
    public function addUser()
    {
        return view('users/addUser');
    }
    
    public function usersGrid()
    {
        return view('users/usersGrid');
    }

    public function usersList()
    {
        return view('users/usersList');
    }
    
    public function viewProfile()
    {
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');
        $response = Http::withToken($token)->get("{$apiBase}/api/get-profile");
        $data = $response->json();
        $data['apiBase'] = $apiBase;        
        return view('users/viewProfile',$data);
    }
    
    public function updateProfile(Request $request)
    {
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');
        $userId = $request->input('user_id', session('id'));
        
        try{
            $request->validate([
                'fullname' => 'required|string|max:255',
                'email'    => 'required|email|max:255',
                'phone'    => 'required|string|max:30',
            ]);
         } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                    ->withErrors($e->validator)
                    ->withInput()
                    ->with('active_tab', 'pills-change-passwork');
        }

        $multipart = [
            ['name' => 'fullname', 'contents' => $request->input('fullname')],
            ['name' => 'email', 'contents' => $request->input('email')],
            ['name' => 'phone', 'contents' => $request->input('phone')],
            ['name' => 'pancard_no', 'contents' => $request->input('pancard_no')],
            ['name' => 'user_id', 'contents' => $userId],
        ];

        $fileFields = [
            'profile_image' => $request->file('profile_image'),
            'aadhar_front'  => $request->file('aadhar_front'),
            'aadhar_back'   => $request->file('aadhar_back'),
            'pancard_image' => $request->file('pancard_image'),
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

        $client = new Client([
            'timeout' => 60,
        ]);

        try {
        $response = $client->request('POST', "{$apiBase}/api/update-profile", [
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

        $status = $response->getStatusCode();
        $body = (string)$response->getBody();
        $headers = $response->getHeaders();

        Log::info("Node update-profile response", ['status'=>$status, 'headers'=>$headers, 'body'=>substr($body,0,2000)]);

        if ($status >= 200 && $status < 300) {
            return redirect()->back()->with('success', 'Profile updated successfully.')->with('active_tab','pills-edit-profile-tab');
        }

        // If we reach here, return useful error
        return redirect()->back()->withErrors("Node API returned HTTP {$status}: " . substr($body,0,1000))->with('active_tab','pills-edit-profile-tab');
    }

    // Accept form submit and forward to Node API
    public function changePassword(Request $request)
    {        
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');
        $userId = $request->input('user_id', session('id'));
        
        try {
            $request->validate([
                'current_pass' => 'required|string|max:255',
                'new_pass'     => 'required|string|max:255',
                'confirm_pass' => 'required|string|max:255|same:new_pass',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('active_tab', 'pills-change-passwork');
        }

        try {
            $response = Http::withToken($token)->post("{$apiBase}/api/change-password", [
                'current_pass' => $request->current_pass,
                'new_pass'     => $request->new_pass,
                'user_id' => $userId
            ]);

            $data = $response->json();

            if ($response->failed()) {
                return redirect()->back()
                    ->with('error', $data['message'] ?? 'Password change failed')
                    ->with('active_tab', 'pills-change-passwork');
            }

            return redirect()->back()
                ->with('success', 'Password updated successfully!')
                ->with('active_tab', 'pills-change-passwork');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Server error: ' . $e->getMessage())
                ->with('active_tab', 'pills-change-passwork');
        }
    }

    public function updateBankAccount(Request $request)
    {
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');
        $userId = $request->input('user_id', session('id'));

        // minimal validation for required non-file fields
        try{            
            $request->validate([
                'account_holder_name' => 'required|string|max:255',
                'account_no'    => 'required|string|max:30',
                'ifsc'    => 'required|string|max:30',
                'bank_name'    => 'required|string|max:30',
            ]);
         } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                    ->withErrors($e->validator)
                    ->withInput()
                    ->with('active_tab', 'pills-change-passwork');
        }

        // Build multipart array
        $multipart = [
            ['name' => 'account_holder_name', 'contents' => $request->input('account_holder_name')],
            ['name' => 'account_no', 'contents' => $request->input('account_no')],
            ['name' => 'ifsc', 'contents' => $request->input('ifsc')],
            ['name' => 'bank_name', 'contents' => $request->input('bank_name')],
            ['name' => 'branch_name', 'contents' => $request->input('branch_name')],
            ['name' => 'bank_acc_id', 'contents' => $request->input('bank_acc_id')],
            ['name' => 'user_id', 'contents' => $userId],
        ];
        
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
            $response = $client->request('POST', "{$apiBase}/api/update-bankaccount", [
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

            $json = json_decode($body, true);
            $msg = $json['message'] ?? 'Success';

            return redirect()->back()
                ->with('success', $msg)
                ->with('active_tab','pills-notification');
        }

        return redirect()->back()->withErrors("Node API returned HTTP {$status}: " . substr($body,0,1000))->with('active_tab','pills-edit-profile-tab');
    }

    public function show($id = null)
    {
        $token = session('token');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        // Use session user_id if $id is not provided
        $userId = $id ?? session('user_id');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/api/user/{$userId}");

            if ($response->failed()) {
                $error = $response->json()['message'] ?? 'Failed to fetch user data';
                return redirect()->back()->with('error', $error);
            }

            $user = $response->json()['data'] ?? null;

            if (!$user) {
                return redirect()->back()->with('error', 'User data not found');
            }

            return view('users/viewProfile', [
                'status'  => 'ok',
                'user'    => $user,
                'apiBase' => $apiBase,
                'id'      => $id, // might be null for normal users
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }


}
