<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentWindow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class PaymentWindowController extends Controller
{
    public function list()
    {
        $data['paymentWindows'] = PaymentWindow::orderBy('id', 'desc')->get();
        return view('admin.payment-window.index', $data);
    }

    public function store(Request $request)
    {
        $purifiedData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'name' => 'required|integer|min:1:max:10',
        ];
        $message = [
            'name.required' => 'Minute field is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $paymentWindow = new PaymentWindow();
        $paymentWindow->name = $request->name;
        $paymentWindow->save();
        return back()->with('success', 'Added Successfully');
    }

    public function update(Request $request, $id)
    {
        $purifiedData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'name' => 'required|integer|min:1:max:10',
        ];
        $message = [
            'name.required' => 'Minute field is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $window = PaymentWindow::findOrFail($id);
        $window->name = $request->name;
        $window->save();

        return back()->with('success', 'Updated Successfully');
    }

    public function delete(Request $request, $id)
    {
        $window = PaymentWindow::with('advertises')->where('id', $id)->firstOrFail();
        if (count($window->advertises) > 0) {
            return back()->with('warning', 'Windows has lot of advertise');
        }
        $window->delete();
        return back()->with('success', 'Delete Successfully');
    }
}
