<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\NotifyTemplate;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function index()
    {
        return view($this->theme . 'user.setting.index');
    }

    public function settingNotify()
    {
        $data['templates'] = NotifyTemplate::where('firebase_notify_status', 1)->where('notify_for', 0)->get()->unique('template_key');
        return view($this->theme . 'user.setting.notifyTemplate', $data);
    }

    public function settingNotifyUpdate(Request $request)
    {

        $user = $this->user;
        $user->notify_active_template = $request->access;
        $user->save();

        session()->flash('success', 'Updated Successfully');
        return back();
    }
}
