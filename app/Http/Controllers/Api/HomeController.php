<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Advertisment;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\Fund;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userid = $request->user_id;

        $trades = Trade::where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })
            ->selectRaw('COUNT(id) AS totalTrade')
            ->selectRaw('COUNT(CASE WHEN status IN (0, 1, 5, 6, 7) THEN id END) AS runningTrade')
            ->selectRaw('COUNT(CASE WHEN status IN (3, 4, 8) THEN id END) AS completeTrade')
            ->orderBy('id', 'desc')
            ->first();

        $advertise = Advertisment::where("user_id", $userid)
            ->selectRaw('COUNT(id) AS totalAdvertise')
            ->orderBy('id', 'desc')
            ->first();

        $recentTrades = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'advertise'])
            ->where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        $data = [
            'trades' => $trades,
            'advertise' => $advertise,
            'recentTrades' => $recentTrades,
        ];

        return response()->json($data);
    }

    public function transactions($code = null)
{
    $transactions = $this->user->transactions()->orderBy('id', 'DESC')
        ->when($code != null, function ($query) use ($code) {
            return $query->where('code', $code);
        })
        ->paginate(config('basic.paginate'));

    return response()->json(['transactions' => $transactions]);
}

public function fundHistory()
{
    $funds = Fund::where('user_id', $this->user->id)
        ->where('status', '!=', 0)
        ->orderBy('id', 'DESC')
        ->with('gateway')
        ->paginate(config('basic.paginate'));

    return response()->json(['funds' => $funds]);
}
}
