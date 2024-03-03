@extends($theme.'layouts.app')
@section('title',trans($title))

@section('content')
    <!-- contact section -->
    @if(isset($contact))
        <div class="contact-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-4">
                        <div class="info-box">
                            <div class="icon-box">
                                <i class="fal fa-map-marker-alt"></i>
                            </div>
                            <div class="text">
                                <h5>@lang('Address')</h5>
                                <p>@lang($contact->address)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="info-box">
                            <div class="icon-box">
                                <i class="fal fa-envelope"></i>
                            </div>
                            <div class="text">
                                <h5>@lang('Email')</h5>
                                <p>@lang($contact->email)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="info-box">
                            <div class="icon-box">
                                <i class="fal fa-phone-alt"></i>
                            </div>
                            <div class="text">
                                <h5>@lang('Phone')</h5>
                                <p>@lang($contact->phone)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="text-box">
                            <div class="header-text">
                                <h5>@lang(@$contact->heading)</h5>
                                <h3>@lang(@$contact->sub_heading)</h3>
                                <p>
                                    @lang(@$contact->short_description)
                                </p>
                                @if(isset($contentDetails['social']))
                                    <div class="social-links mt-5">
                                        @foreach($contentDetails['social'] as $data)
                                            <a href="{{@$data->content->contentMedia->description->link}}">
                                                <i class="{{@$data->content->contentMedia->description->icon}}"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <form action="{{route('contact.send')}}" method="post">
                            @csrf
                            <div class="row g-3">
                                <div class="input-box col-md-6">
                                    <input class="form-control" type="text" name="name" value="{{old('name')}}"
                                           placeholder="@lang('Full name')"/>
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <input class="form-control" type="email" name="email" value="{{old('email')}}"
                                           placeholder="@lang('Email address')"/>
                                    @error('email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="input-box col-md-12">
                                    <input class="form-control" type="text" name="subject"
                                           value="{{old('subject')}}" placeholder="@lang('Subject')"/>
                                    @error('subject')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="input-box col-12">
                                    <textarea class="form-control" cols="30" rows="3" name="message"
                                              placeholder="@lang('Your message')">{{old('message')}}</textarea>
                                    @error('message')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="input-box col-12">
                                    <button class="btn-custom" type="submit">@lang('submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
