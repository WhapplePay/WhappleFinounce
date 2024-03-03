<!-- footer section -->
<footer class="footer-section">
    <div class="container">
        <div class="row gy-5 gy-lg-0">
            <div class="col-lg-3 col-md-6">
                <div class="footer-box">
                    <a class="navbar-brand" href="javascript:void(0)"> <img
                            src="{{ getFile(config('location.logoIcon.path') . 'logo.png') }}" alt=""/></a>
                    @if(isset($contactUs['contact-us'][0]) && $contact = $contactUs['contact-us'][0])
                        <p class="company-bio">
                            @lang(strip_tags(@$contact->description->footer_short_details))
                        </p>
                    @endif
                    @if(isset($contentDetails['social']))
                        <div class="social-links">
                            @foreach($contentDetails['social'] as $data)
                                <a href="{{@$data->content->contentMedia->description->link}}">
                                    <i class="{{@$data->content->contentMedia->description->icon}}"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-box">
                    <h5>@lang('Quick Links')</h5>
                    <ul>
                        <li><a href="{{ route('home') }}">- @lang('Home')</a></li>
                        <li><a href="{{ route('about') }}">- @lang('About')</a></li>
                        <li><a href="{{ route('blog') }}">- @lang('Blog')</a></li>
                        <li><a href="{{ route('contact') }}">- @lang('Contact')</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">

                @isset($contentDetails['support'])
                    <div class="footer-box">
                        <h5>@lang('Support')</h5>
                        <ul>
                            @foreach($contentDetails['support'] as $data)
                                <li>
                                    <a href="{{route('getLink', [slug($data->description->title), $data->content_id])}}">@lang($data->description->title)</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endisset
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-box">
                    <h5>@lang('Newsletter')</h5>
                    <div class="news-letter">
                        <form action="{{ route('subscribe') }}" method="post">
                            @csrf
                            <input type="email" name="email" placeholder="@lang('Email Address')" class="form-control"/>
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <button class="btn-custom">@lang('subscribe now')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-flex copyright justify-content-start justify-content-sm-between">
            <div class="col-md-7">
              <span>
                     @lang('Copyright') &copy; {{date('Y')}} @lang($basic->site_title) @lang('All Rights Reserved')
                  </span>
            </div>
            <div class="col-md-5 mt-3 mt-sm-0 text-sm-end text-start">
                @forelse($languages as $item)
                    <a href="{{route('language',$item->short_name)}}" class="language">@lang($item->name)</a>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</footer>
