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

    public function unverifiedUser(Request $request)
    {
        $token = session('token');      
        $user = session('user');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/api/reports/userlist");

            $data = $response->json();
            $transactions = $data['data'] ?? [];

            // Show all except verified users
            $unverifiedUsers = array_filter($transactions, function ($transaction) {
                return !isset($transaction['kyc_status']) || strtolower($transaction['kyc_status']) !== 'verified';
            });

            return view('dashboard.unverifieduser', [
                'users' => $unverifiedUsers,
                'is_admin' => $user['is_admin'],
                'apiBase' => $apiBase
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function verifiedUser(Request $request)
    {
        $token = session('token');      
        $user = session('user');
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/api/reports/userlist");

            $data = $response->json();
            $transactions = $data['data'] ?? [];

            // Filter: only users with kyc_status = "verified"
            $verifiedUsers = array_filter($transactions, function ($transaction) {
                return isset($transaction['kyc_status']) && strtolower($transaction['kyc_status']) === 'verified';
            });

            return view('dashboard.verifieduser', [
                'users' => $verifiedUsers,
                'is_admin' => $user['is_admin'],
                'apiBase' => $apiBase
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function changeUserStatus(Request $request)
    {
        $token = session('token');      
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        $userId = $request->id;

        // Call Node API
        $response = Http::withToken($token)->post("{$apiBase}/api/change-user-status", [
            'id' => $userId            
        ]);

        $res = $response->json();

        if(isset($res['status']) && $res['status'] == 'ok'){
            return redirect()->back()->with('success', 'User status updated successfully.');
        } else {
            $message = $res['message'] ?? 'Failed to update status.';
            return redirect()->back()->with('error', $message);
        }
    }

    public function kycVerify(Request $request)
    {
        $token = session('token');      
        $apiBase = rtrim(env('NODE_API_URL'), '/');

        $userId = $request->id;

        $response = Http::withToken($token)->post("{$apiBase}/api/kyc-verify", [
            'id' => $userId
        ]);

        $res = $response->json();

        if(isset($res['status']) && $res['status'] == 'ok'){
            return redirect()->back()->with('success', 'KYC Verified Successfully.');
        } else {
            $message = $res['message'] ?? 'Failed to verify KYC.';
            return redirect()->back()->with('error', $message);
        }
    }
    
}
