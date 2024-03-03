<?php

namespace App\Http\Controllers;

use App\Models\Advertisment;
use App\Models\Content;
use App\Models\Currency;
use App\Models\Gateway;
use App\Models\Language;
use App\Models\Template;
use App\Models\Subscriber;
use App\Http\Traits\Notify;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ContentDetails;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    use Notify;

    public function __construct()
    {
        $this->theme = template();
    }

    public function index()
    {
        $templateSection = ['hero', 'about-us', 'how-it-work', 'buy-sell', 'feature', 'faq', 'testimonial', 'blog', 'call', 'contact-us'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $contentSection = ['about-us', 'how-it-work', 'feature', 'faq', 'testimonial', 'blog', 'support'];
        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');

        $data['buy'] = Advertisment::where('status', 1)->where('type', 'sell')->orderBy('id', 'desc')->take(8)->get();

        return view($this->theme . 'home', $data);
    }


    public function about()
    {

        $templateSection = ['about-us', 'feature', 'testimonial', 'call'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $contentSection = ['feature', 'feature', 'testimonial'];
        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');
        return view($this->theme . 'about', $data);
    }


    public function blog(Request $request)
    {
        $search = $request->all();
        $data['title'] = "Blog";
        $contentSection = ['blog'];

        $templateSection = ['blog'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->when($search, function ($query) use ($search) {
                return $query->where("description", 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere("created_at", 'LIKE', '%' . $search['search'] . '%');
            })
            ->get()->groupBy('content.name');

        return view($this->theme . 'blog', $data);
    }

    public function blogDetails($slug = null, $id)
    {
        $getData = Content::findOrFail($id);

        $contentSection = [$getData->name];
        $contentDetail = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->where('content_id', $getData->id)
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');

        $singleItem['title'] = @$contentDetail[$getData->name][0]->description->title;
        $singleItem['description'] = @$contentDetail[$getData->name][0]->description->description;
        $singleItem['date'] = dateTime(@$contentDetail[$getData->name][0]->created_at, 'd M, Y');
        $singleItem['image'] = getFile(config('location.content.path') . @$contentDetail[$getData->name][0]->content->contentMedia->description->image);


        $contentSectionPopular = ['blog'];
        $popularContentDetails = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->where('content_id', '!=', $getData->id)
            ->whereHas('content', function ($query) use ($contentSectionPopular) {
                return $query->whereIn('name', $contentSectionPopular);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');

        return view($this->theme . 'blogDetails', compact('singleItem', 'popularContentDetails'));
    }


    public function faq()
    {

        $templateSection = ['faq'];
        $data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

        $contentSection = ['faq'];
        $data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');

        $data['increment'] = 1;
        return view($this->theme . 'faq', $data);
    }

    public function contact()
    {
        $templateSection = ['contact-us'];
        $templates = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');
        $title = 'Contact Us';
        $contact = @$templates['contact-us'][0]->description;

        return view($this->theme . 'contact', compact('title', 'contact'));
    }

    public function contactSend(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:91',
            'subject' => 'required|max:100',
            'message' => 'required|max:1000',
        ]);
        $requestData = Purify::clean($request->except('_token', '_method'));

        $basic = (object)config('basic');
        $basicEmail = $basic->sender_email;

        $name = $requestData['name'];
        $email_from = $requestData['email'];
        $subject = $requestData['subject'];
        $message = $requestData['message'] . "<br>Regards<br>" . $name;
        $from = $email_from;

        $headers = "From: <$from> \r\n";
        $headers .= "Reply-To: <$from> \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $to = $basicEmail;

        if (@mail($to, $subject, $message, $headers)) {
        } else {
        }

        return back()->with('success', 'Mail has been sent');
    }

    public function getLink($getLink = null, $id)
    {
        $getData = Content::findOrFail($id);

        $contentSection = [$getData->name];
        $contentDetail = ContentDetails::select('id', 'content_id', 'description', 'created_at')
            ->where('content_id', $getData->id)
            ->whereHas('content', function ($query) use ($contentSection) {
                return $query->whereIn('name', $contentSection);
            })
            ->with(['content:id,name',
                'content.contentMedia' => function ($q) {
                    $q->select(['content_id', 'description']);
                }])
            ->get()->groupBy('content.name');

        $title = @$contentDetail[$getData->name][0]->description->title;
        $description = @$contentDetail[$getData->name][0]->description->description;
        return view($this->theme . 'getLink', compact('contentDetail', 'title', 'description'));
    }

    public function subscribe(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:255|unique:subscribers'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect(url()->previous() . '#subscribe')->withErrors($validator);
        }
        $data = new Subscriber();
        $data->email = $request->email;
        $data->save();
        return redirect(url()->previous() . '#subscribe')->with('success', 'Subscribed Successfully');
    }

    public function language($code)
    {
        $language = Language::where('short_name', $code)->first();
        if (!$language) $code = 'US';
        session()->put('trans', $code);
        session()->put('rtl', $language ? $language->rtl : 0);
        return redirect()->back();
    }

    public function buy(Request $request, $gatewayId = null)
    {
        $search = $request->all();
        $data['cryptos'] = Currency::where('status', 1)->where('flag', 1)->orderBy('name')->get();
        $data['fiats'] = Currency::where('status', 1)->where('flag', 0)->orderBy('name')->get();
        $data['gateways'] = Gateway::where('status', 1)->orderBy('name')->get();
        $data['locations'] = User::select('address')->where('status', 1)->orderBy('address')->groupBy('address')->get();
        $data['buy'] = Advertisment::where('status', 1)->where('type', 'sell')
            ->when(isset($search['seller']), function ($query) use ($search) {
                $query->whereHas('user', function ($qq) use ($search) {
                    $qq->where('username', 'LIKE', '%' . $search['seller'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $search['seller'] . '%');
                });
            })
            ->when(isset($search['crypto']), function ($query) use ($search) {
                return $query->where("crypto_id", $search['crypto']);
            })
            ->when(isset($search['fiat']), function ($query) use ($search) {
                return $query->where("fiat_id", $search['fiat']);
            })
            ->when(isset($search['gateway']), function ($query) use ($search) {
                return $query->where("gateway_id", $search['gateway']);
            })
            ->when(isset($search['location']), function ($query) use ($search) {
                $query->whereHas('user', function ($qq) use ($search) {
                    $qq->where('address', 'LIKE', '%' . $search['location'] . '%');
                });
            })
            ->when($gatewayId != null, function ($query) use ($gatewayId) {
                return $query->whereJsonContains("gateway_id", $gatewayId);
            })
            ->orderBy('id', 'desc')->paginate(config('basic.paginate'));
        return view($this->theme . 'buy', $data);
    }

    public function sell(Request $request, $gatewayId = null)
    {
        $search = $request->all();
        $data['cryptos'] = Currency::where('status', 1)->where('flag', 1)->orderBy('name')->get();
        $data['fiats'] = Currency::where('status', 1)->where('flag', 0)->orderBy('name')->get();
        $data['gateways'] = Gateway::where('status', 1)->orderBy('name')->get();
        $data['locations'] = User::select('address')->where('status', 1)->orderBy('address')->groupBy('address')->get();
        $data['buy'] = Advertisment::where('status', 1)->where('type', 'buy')
            ->when(isset($search['buyer']), function ($query) use ($search) {
                $query->whereHas('user', function ($qq) use ($search) {
                    $qq->where('username', 'LIKE', '%' . $search['buyer'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $search['buyer'] . '%');
                });
            })
            ->when(isset($search['crypto']), function ($query) use ($search) {
                return $query->where("crypto_id", $search['crypto']);
            })
            ->when(isset($search['fiat']), function ($query) use ($search) {
                return $query->where("fiat_id", $search['fiat']);
            })
            ->when(isset($search['gateway']), function ($query) use ($search) {
                return $query->where("gateway_id", $search['gateway']);
            })
            ->when(isset($search['location']), function ($query) use ($search) {
                $query->whereHas('user', function ($qq) use ($search) {
                    $qq->where('address', 'LIKE', '%' . $search['location'] . '%');
                });
            })
            ->when($gatewayId != null, function ($query) use ($gatewayId) {
                return $query->whereJsonContains("gateway_id", $gatewayId);
            })
            ->orderBy('id', 'desc')->paginate(config('basic.paginate'));
        return view($this->theme . 'sell', $data);
    }

}
