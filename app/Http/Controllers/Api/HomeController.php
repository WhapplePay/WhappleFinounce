<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Advertisment;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\Fund;
use App\Models\User;

use App\Models\Wallet;
// use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $phoneNumber = $request->phone_number; // Assuming phone number is sent in the request
    \Log::info(['data'=>$request->all()]);
            // Fetch user based on the phone number
            $user = User::where('phone', $phoneNumber)->first();
    
            if (!$user) {
                return response()->json(["status" => 404, "message" => "User not found"]);
            }
    
            $userId = $user->id;
    
            $trades = Trade::where(function ($query) use ($userId) {
                    $query->where("sender_id", $userId)
                        ->orWhere("owner_id", $userId);
                })
                ->selectRaw('COUNT(id) AS totalTrade')
                ->selectRaw('COUNT(CASE WHEN status IN (0, 1, 5, 6, 7) THEN id END) AS runningTrade')
                ->selectRaw('COUNT(CASE WHEN status IN (3, 4, 8) THEN id END) AS completeTrade')
                ->orderBy('id', 'desc')
                ->first();
    
            $advertise = Advertisment::where("user_id", $userId)
                ->selectRaw('COUNT(id) AS totalAdvertise')
                ->orderBy('id', 'desc')
                ->first();
    
            $recentTrades = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'advertise'])
                ->where(function ($query) use ($userId) {
                    $query->where("sender_id", $userId)
                        ->orWhere("owner_id", $userId);
                })
                ->orderBy('id', 'desc')
                ->limit(8)
                ->get();
    
            $userWallets = Wallet::where('user_id', $userId)->with('crypto')->latest()->get();
    
            $formattedWallets = $userWallets->map(function ($wallet) {
                $cryptoDetails = $wallet->crypto;
                return [
                    'wallet_id' => $wallet->id,
                    'crypto_id' => $cryptoDetails->id,
                    'crypto_name' => $cryptoDetails->name,
                    'crypto_symbol' => $cryptoDetails->symbol,
                    'balance' => $wallet->balance,
                ];
            });
    
            $data = [
                 'wallets' => $formattedWallets,
            ];
    
            return response()->json(["status" => 200, "data" => $data]);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "message" => "Error: " . $e->getMessage()]);
        }
    }
    

    public function transaction(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user_id)
            ->orderBy('id', 'DESC')
            ->get();
    
        return response()->json(['transactions' => $transactions]);
    }
    
    public function fundHistory(Request $request)
    {
        $funds = Fund::where('user_id', $request->user_id)
            ->where('status', '!=', 0)
            ->orderBy('id', 'DESC')
            ->with('gateway')
            ->paginate(config('basic.paginate'));
    
        return response()->json(['data' => $funds->items()]); 
    }
    
    public function deposit(Request $request){

        $data = 1;
    }
}
