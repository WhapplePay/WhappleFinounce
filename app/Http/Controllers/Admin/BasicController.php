<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload;
use App\Models\Configure;
use App\Models\Referral;
use Illuminate\Support\Facades\Artisan;
use Image;
use Session;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;

class BasicController extends Controller
{
    use Upload;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $timeZone = timezone_identifiers_list();
        $control = Configure::firstOrNew();
        $control->time_zone_all = $timeZone;
        return view('admin.basic-controls', compact('control'));
    }

    public function updateConfigure(Request $request)
    {
        $configure = Configure::firstOrNew();
        $reqData = Purify::clean($request->except('_token', '_method'));
        $request->validate([
            'site_title' => 'required',
            'base_color' => 'required',
            'time_zone' => 'required',
            'fraction_number' => 'required|integer',
            'paginate' => 'required|integer'
        ]);

        config(['basic.site_title' => $reqData['site_title']]);
        config(['basic.base_color' => $reqData['base_color']]);
        config(['basic.secondary_color' => $reqData['secondary_color']]);
        config(['basic.time_zone' => $reqData['time_zone']]);
        config(['basic.force_ssl' => $reqData['force_ssl']]);
        config(['basic.fraction_number' => (int)$reqData['fraction_number']]);
        config(['basic.paginate' => (int)$reqData['paginate']]);
        config(['basic.tradeCharge' => (int)$reqData['tradeCharge']]);
        config(['basic.trade_extra_time' => (int)$reqData['trade_extra_time']]);
        config(['basic.incompleteLimit' => (int)$reqData['incomplete_limit']]);
        config(['basic.ad_rate_update' => (int)$reqData['ad_rate_update']]);

        config(['basic.error_log' => (int)$reqData['error_log']]);
        config(['basic.strong_password' => (int)$reqData['strong_password']]);
        config(['basic.registration' => (int)$reqData['registration']]);
        config(['basic.is_active_cron_notification' => (int)$reqData['cron_set_up_pop_up']]);


        $configure->fill($reqData)->save();

        $fp = fopen(base_path() . '/config/basic.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('basic'), true) . ';');
        fclose($fp);


        $envPath = base_path('.env');
        $env = file($envPath);
        $env = $this->set('APP_DEBUG', ($configure->error_log == 1) ? 'true' : 'false', $env);
        $env = $this->set('APP_TIMEZONE', "'".$reqData['time_zone']."'", $env);

        $fp = fopen($envPath, 'w');
        fwrite($fp, implode($env));
        fclose($fp);

        session()->flash('success', ' Updated Successfully');
        Artisan::call('optimize:clear');
        return back();
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


    public function manageTheme()
    {
        $data['configure'] = Configure::firstOrNew();
        $data['theme'] = config('theme');
        return view('admin.manage-theme', $data);
    }

    public function activateTheme(Request $request, $name)
    {
        config(['basic.theme' => $name]);
        $fp = fopen(base_path() . '/config/basic.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('basic'), true) . ';');
        fclose($fp);

        $configure = Configure::firstOrNew();
        $configure->theme = $name;
        $configure->save();

        session()->flash('success', 'Theme Activated Successfully');
        Artisan::call('optimize:clear');
        return back();
    }

    public function logoSeo()
    {
        $seo = (object)config('seo');
        return view('admin.logo', compact('seo'));
    }

    public function logoUpdate(Request $request)
    {
        if ($request->hasFile('image')) {
            try {
                $old = 'logo.png';
                $this->uploadImage($request->image, config('location.logo.path'), null, $old, null, $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Logo could not be uploaded.');
            }
        }

        if ($request->hasFile('admin_logo')) {
            try {
                $old = 'admin-logo.png';
                $this->uploadImage($request->admin_logo, config('location.logo.path'), null, $old, null, $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Adnub Logo could not be uploaded.');
            }
        }
        if ($request->hasFile('favicon')) {
            try {
                $old = 'favicon.png';
                $this->uploadImage($request->favicon, config('location.logo.path'), null, $old, null, $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'favicon could not be uploaded.');
            }
        }
        return back()->with('success', 'Logo has been updated.');
    }


    public function breadcrumb()
    {
        return view('admin.banner');
    }

    public function breadcrumbUpdate(Request $request)
    {
        if ($request->hasFile('banner')) {
            try {
                $old = 'banner.jpg';
                $this->uploadImage($request->banner, config('location.logo.path'), null, $old, null, $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Banner could not be uploaded.');
            }
        }
        return back()->with('success', 'Banner has been updated.');
    }


    public function seoUpdate(Request $request)
    {

        $reqData = Purify::clean($request->except('_token', '_method'));
        $request->validate([
            'meta_keywords' => 'required',
            'meta_description' => 'required',
            'social_title' => 'required',
            'social_description' => 'required'
        ]);
        config(['seo.meta_keywords' => $request['meta_keywords']]);
        config(['seo.meta_description' => $request['meta_description']]);
        config(['seo.social_title' => $request['social_title']]);
        config(['seo.social_description' => $request['social_description']]);


        if ($request->hasFile('meta_image')) {
            try {
                $old = config('seo.meta_image');
                $meta_image = $this->uploadImage($request->meta_image, config('location.logo.path'), null, $old, null, $old);
                config(['seo.meta_image' => $meta_image]);
            } catch (\Exception $exp) {
                return back()->with('error', 'favicon could not be uploaded.');
            }
        }

        $fp = fopen(base_path() . '/config/seo.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('seo'), true) . ';');
        fclose($fp);
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return back()->with('success', 'Favicon has been updated.');

    }

    public function referralCommission()
    {
        $referrals = Referral::get();
        return view('admin.plan.referral-commission', compact('referrals'));

    }

    public function referralCommissionStore(Request $request)
    {
        $request->validate([
            'level*' => 'required|integer|min:1',
            'percent*' => 'required|numeric',
            'commission_type' => 'required',
        ]);

        Referral::where('commission_type', $request->commission_type)->delete();

        for ($i = 0; $i < count($request->level); $i++) {
            $referral = new Referral();
            $referral->commission_type = $request->commission_type;
            $referral->level = $request->level[$i];
            $referral->percent = $request->percent[$i];
            $referral->save();
        }

        return back()->with('success', 'Level Bonus Has been Updated.');
    }

    public function api()
    {
        $data['configure'] = Configure::select(['public_key', 'private_key', 'merchant_id'])->firstOrFail();
        return view('admin.api.index', $data);
    }

    public function updateApi(Request $request)
    {
        $request->validate([
            'public_key' => 'required',
            'private_key' => 'required',
            'merchant_id' => 'required',
        ]);
        $configure = Configure::firstOrNew();
        $configure->public_key = $request->public_key;
        $configure->private_key = $request->private_key;
        $configure->merchant_id = $request->merchant_id;
        $configure->save();
        return back()->with('success', 'Updated Successfully');
    }

}
