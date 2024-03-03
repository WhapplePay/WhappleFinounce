<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\User;


class ProfileController extends Controller
{
    use Upload, Notify;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function page($id = 1)
    {
        if($this->user->id == $id){
            return redirect()->route('user.home')->with('warning','You are not supported');
        }
        $data['user'] = User::with('advertise')->findOrFail($id);
        return view($this->theme . 'user.public-profile.page', $data);
    }
}
