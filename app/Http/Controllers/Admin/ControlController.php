<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Stevebauman\Purify\Facades\Purify;
use Validator;
class ControlController extends Controller
{


    public function pluginConfig()
    {
        $control = Configure::firstOrNew();
        return view('admin.plugin_panel.pluginConfig', compact('control'));
    }

    public function tawkConfig(Request $request)
    {
        $basicControl = basicControl();

        if ($request->isMethod('get')) {
            // $currencies = Currency::select('id', 'code', 'name')->where('is_active', 1)->get();
            return view('admin.plugin_panel.tawkControl', compact('basicControl'));
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'tawk_id' => 'required|min:3',
                'tawk_status' => 'nullable|integer|min:0|in:0,1',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $purifiedData = (object)$purifiedData;

            $basicControl->tawk_id = $purifiedData->tawk_id;
            $basicControl->tawk_status = $purifiedData->tawk_status;
            $basicControl->save();

            return back()->with('success', 'Successfully Updated');
        }
    }

    public function fbMessengerConfig(Request $request)
    {
        $basicControl = basicControl();

        if ($request->isMethod('get')) {
            return view('admin.plugin_panel.fbMessengerControl', compact('basicControl'));
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'fb_messenger_status' => 'nullable|integer|min:0|in:0,1',
                'fb_app_id' => 'required|min:3',
                'fb_page_id' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $purifiedData = (object)$purifiedData;

            $basicControl->fb_app_id = $purifiedData->fb_app_id;
            $basicControl->fb_page_id = $purifiedData->fb_page_id;
            $basicControl->fb_messenger_status = $purifiedData->fb_messenger_status;

            $basicControl->save();

            return back()->with('success', 'Successfully Updated');
        }
    }

    public function googleRecaptchaConfig(Request $request)
    {
        $basicControl = basicControl();

        if ($request->isMethod('get')) {
            return view('admin.plugin_panel.googleReCaptchaControl', compact('basicControl'));
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'reCaptcha_status_login' => 'nullable|integer|min:0|in:0,1',
                'reCaptcha_status_registration' => 'nullable|integer|min:0|in:0,1',
                'NOCAPTCHA_SECRET' => 'required|min:3',
                'NOCAPTCHA_SITEKEY' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $purifiedData = (object)$purifiedData;

            $basicControl->reCaptcha_status_login = $purifiedData->reCaptcha_status_login;
            $basicControl->reCaptcha_status_registration = $purifiedData->reCaptcha_status_registration;
            $basicControl->save();


            $envPath = base_path('.env');
            $env = file($envPath);
            $env = $this->set('NOCAPTCHA_SECRET', $purifiedData->NOCAPTCHA_SECRET, $env);
            $env = $this->set('NOCAPTCHA_SITEKEY', $purifiedData->NOCAPTCHA_SITEKEY, $env);
            $fp = fopen($envPath, 'w');
            fwrite($fp, implode($env));
            fclose($fp);

            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return back()->with('success', 'Successfully Updated');
        }
    }

    public function googleAnalyticsConfig(Request $request)
    {
        $basicControl = basicControl();

        if ($request->isMethod('get')) {
            return view('admin.plugin_panel.analyticControl', compact('basicControl'));
        } elseif ($request->isMethod('post')) {
            $purifiedData = Purify::clean($request->all());

            $validator = Validator::make($purifiedData, [
                'MEASUREMENT_ID' => 'required|min:3',
                'analytic_status' => 'nullable|integer|min:0|in:0,1',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $purifiedData = (object)$purifiedData;

            $basicControl->MEASUREMENT_ID = $purifiedData->MEASUREMENT_ID;
            $basicControl->analytic_status = $purifiedData->analytic_status;
            $basicControl->save();

            return back()->with('success', 'Successfully Updated');
        }
    }


    private function set($key, $value, $env)
    {
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            if ($entry[0] == $key) {
                $env[$env_key] = $key . "=" . $value . "\n";
            } else {
                $env[$env_key] = $env_value;
            }
        }
        return $env;
    }


}
