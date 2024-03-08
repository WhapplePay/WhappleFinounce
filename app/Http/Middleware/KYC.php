<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Validator;
use Auth;

class KYC
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $basic = (object) config('basic');

        $validator = Validator::make($request->all(),[]);

        if($basic->identity_verification == 1 && Auth::user()->identity_verify != '2'){
            $validator->errors()->add('identity', '1');
            session()->flash('error', 'Please you must verify you identity before performing that action ');
            return redirect()->route('user.identityVerify')->withErrors($validator)->withInput();
        }

        // if ($basic->address_verification == 1 && Auth::user()->address_verify != '2') {
        //     $validator->errors()->add('addressVerification', '1');
        //     session()->flash('error', 'Please you must verify you address localisation before performing that action ');
        //     return redirect()->route('user.addressVerification')->withErrors($validator)->withInput();
        // }
        

        return $next($request);
    }
}
