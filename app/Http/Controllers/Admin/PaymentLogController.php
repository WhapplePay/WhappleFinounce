<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Models\Deposit;
use App\Models\Fund;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Stevebauman\Purify\Facades\Purify;

class PaymentLogController extends Controller
{
    use Notify;

    protected function depositData()
    {
        $deposits = Fund::with(['user']);
        $request = request();
        //search
        if ($request->search) {
            $search = request()->search;
            $deposits = $deposits->where(function ($q) use ($search) {
                $q->where('trx', 'like', "%$search%")->orWhereHas('user', function ($user) use ($search) {
                    $user->where('username', 'like', "%$search%");
                });
            });
        }
        //date search
        $deposits = $deposits->dateFilter();

        return [
            'data' => $deposits->orderBy('id', 'desc')->with('crypto')->paginate(config('basic.paginate')),
        ];
    }

    public function index($id = null)
    {
        $page_title = "Deposit Logs";
        $funds = Fund::with(['user', 'crypto'])
            ->when($id != null, function ($query) use ($id) {
                return $query->where("user_id", $id);
            })
            ->paginate(config('basic.paginate'));
        return view('admin.payment.logs', compact('funds', 'page_title'));
    }

    public function search(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $funds = Fund::when(isset($search['name']), function ($query) use ($search) {
            return $query->whereHas('user', function ($q) use ($search) {
                $q->where('email', 'LIKE', "%{$search['name']}%")
                    ->orWhere('username', 'LIKE', "%{$search['name']}%");
            });
        })
            ->when(isset($search['trx']), function ($query) use ($search) {
                return $query->where("trx", 'LIKE', '%' . $search['trx'] . '%');
            })
            ->with('user')
            ->paginate(config('basic.paginate'));
        $funds->appends($search);
        $page_title = "Search Payment Logs";
        return view('admin.payment.logs', compact('funds', 'page_title'));
    }
}
