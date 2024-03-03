<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload;
use App\Models\Currency;
use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;


class PaymentMethodController extends Controller
{
    use Upload;

    public function index(Gateway $gateway)
    {
        $data['methods'] = Gateway::orderBy('name', 'asc')->get();
        $data['page_title'] = 'Payment Methods';
        return view('admin.payment_methods.index', $data);
    }

    public function deactivate(Request $request)
    {
        $data = Gateway::where('code', $request->code)->firstOrFail();

        if ($data->status == 1) {
            $data->status = 0;
        } else {
            $data->status = 1;
        }
        $data->save();

        return back()->with('success', 'Updated Successfully.');
    }

    public function create()
    {
        $data['page_title'] = 'Create Payment Method';
        return view('admin.payment_methods.create', $data);
    }

    public function store(Request $request)
    {
        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));
        $rules = [
            'name' => 'required|max:40',
            'color' => 'required',
        ];
        $message = [
            'name.required' => 'Name field is required',
            'color.required' => 'Color field is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        $input_form = [];
        if ($request->has('field_name')) {
            for ($a = 0; $a < count($request->field_name); $a++) {
                $arr = array();
                $arr['name'] = clean($request->field_name[$a]);
                $arr['label'] = $request->field_name[$a];
                $arr['type'] = $request->type[$a];
                $arr['validation'] = $request->validation[$a];
                $input_form[$arr['name']] = $arr;
            }
        }

        if ($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image, config('location.gateway.path'), config('location.gateway.size'));
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }

        Gateway::create([
            'name' => $request->name,
            'color' => $request->color,
            'image' => $image,
            'status' => $request->status,
            'input_form' => $input_form
        ]);

        return back()->with('success', 'Gateway Create Successfully');
    }

    public function edit($id)
    {
        $data['method'] = Gateway::findOrFail($id);
        $data['page_title'] = 'Edit Payment Methods';
        return view('admin.payment_methods.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'color' => 'required',
        ];

        $getGateway = Gateway::findOrFail($id);
        $this->validate($request, $rules);

        $input_form = [];
        if ($request->has('field_name')) {
            for ($a = 0; $a < count($request->field_name); $a++) {
                $arr = array();
                $arr['name'] = clean($request->field_name[$a]);
                $arr['label'] = $request->field_name[$a];
                $arr['type'] = $request->type[$a];
                $arr['validation'] = $request->validation[$a];
                $input_form[$arr['name']] = $arr;
            }
        }

        if ($request->hasFile('image')) {
            try {
                $old = $getGateway->image ?: null;
                $image = $this->uploadImage($request->image, config('location.gateway.path'), config('location.gateway.size'), $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }


        try {
            $res = $getGateway->update([
                'name' => $request->name,
                'color' => $request->color,
                'image' => $image ?? $getGateway->image,
                'status' => $request->status,
                'input_form' => $input_form,
            ]);

            if (!$res) {
                throw new \Exception('Unexpected error! Please try again.');
            }
            return back()->with('success', 'Gateway data has been updated.');

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

    }
}
