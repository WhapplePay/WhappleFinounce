<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisment;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdvertiseController extends Controller
{
    public function advertiseList(Request $request, $stage = null)
    {
        $search = $request->all();
        $data['advertises'] = Advertisment::with(['user', 'fiatCurrency', 'cryptoCurrency', 'paymentWindow'])
            ->when($stage == 'enable', function ($query) {
                $query->where('status', '1');
            })
            ->when($stage == 'disable', function ($query) {
                $query->where('status', '0');
            })
            ->when(isset($search['username']), function ($query) use ($search) {
                $query->whereHas('user', function ($qq) use ($search) {
                    $qq->where('username', 'LIKE', '%' . $search['username'] . '%');
                });
            })
            ->when(isset($search['email']), function ($query) use ($search) {
                $query->whereHas('user', function ($qq) use ($search) {
                    $qq->where('email', 'LIKE', '%' . $search['email'] . '%');
                });
            })
            ->paginate(config('basic.paginate'));
        return view('admin.advertise.index', $data);
    }

    public function enable($id)
    {
        $advertisement = Advertisment::findOrFail($id);
        $advertisement->status = 1;
        $advertisement->save();
        return back()->with('success', 'Enable Successfully');
    }

    public function disable($id)
    {
        $advertisement = Advertisment::findOrFail($id);
        $advertisement->status = 0;
        $advertisement->save();
        return back()->with('success', 'Disable Successfully');
    }

    public function feedbackList(Request $request, $adId)
    {
        $ad = Advertisment::with(['fiatCurrency', 'cryptoCurrency'])->select(['crypto_id', 'fiat_id'])->findOrFail($adId);
        $data['adId'] = $adId;
        $data['cryptoCode'] = $ad->cryptoCurrency->code;
        $data['fiatCode'] = $ad->fiatCurrency->code;
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $data['feedbacks'] = Feedback::with(['creator', 'reviewer'])->where('advertisement_id', $adId)
            ->when(isset($search['reviewer']), function ($query) use ($search) {
                $query->whereHas('reviewer', function ($qq) use ($search) {
                    $qq->where('email', 'LIKE', '%' . $search['reviewer'] . '%')
                        ->orWhere('username', 'LIKE', '%' . $search['reviewer'] . '%');
                });
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        return view('admin.advertise.feedback.index', $data);
    }

    public function feedbackDelete($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        return back()->with('success', 'Delete Successfully');
    }
}
