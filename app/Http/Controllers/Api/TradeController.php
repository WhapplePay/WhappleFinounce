<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Configure;
use App\Models\Trade;
use App\Models\TradeChat;
use App\Models\Wallet;
use Carbon\Carbon;
use App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    public function runningTrades(Request $request)
    {
        $userid = $request->user_id;
    
        $runningTrades = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'advertise'])
            ->where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })
            ->whereIn("status", [0, 1, 5, 6, 7])
            ->orderBy('id', 'desc')
            ->paginate(config('basic.paginate'));
    
        return response()->json(['running_trades' => $runningTrades]);
    }
    
    public function completedTrades(Request $request)
    {
        $userid = $request->user_id;
    
        $completedTrades = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'advertise'])
            ->where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })
            ->whereIn("status", [3, 4, 8])
            ->orderBy('id', 'desc')
            ->paginate(config('basic.paginate'));
    
        return response()->json(['completed_trades' => $completedTrades]);
    }
}
