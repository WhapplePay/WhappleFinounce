@extends($theme.'layouts.app')
@section('title','Register')
@section('content')
    <!-- login section -->
    <section class="login-section">
        <div class="container h-100">
            <div class="row h-100 justify-content-center">
                <div class="col-lg-6">
                    <div class="form-wrapper d-flex align-items-center h-100">
                        <form action="{{ route('register') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <h5>@lang('register here')</h5>
                                </div>

                                @include('errors.error')
                                @if(session()->get('sponsor') != null)
                                    <div class="col-md-12">
                                        <div class="input-box mb-30">
                                            <label>@lang('Sponsor Name')</label>
                                            <input type="text" name="sponsor" class="form-control" id="sponsor"
                                                   placeholder="{{trans('Sponsor By') }}"
                                                   value="{{session()->get('sponsor')}}" readonly>
                                        </div>
                                    </div>
                                @endif
                                <div class="input-box col-md-6">
                                    <input type="text" name="firstname" value="{{old('firstname')}}"
                                           class="form-control" placeholder="@lang('First Name')"/>
                                    @error('firstname')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <input type="text" name="lastname"
                                           value="{{old('lastname')}}" class="form-control"
                                           placeholder="@lang('Last Name')"/>
                                    @error('lastname')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <input type="text" name="username"
                                           value="{{old('username')}}"
                                           class="form-control" placeholder="@lang('Username')"/>
                                    @error('username')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <input type="email" name="email"
                                           value="{{old('email')}}" class="form-control"
                                           placeholder="@lang('Email Address')"/>
                                    @error('email')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                </div>

                                <div class="input-box col-md-12">
                                    <div class="input-group">
                                        <select class="form-select country_code dialCode-change" name="phone_code"
                                                id="basic-addon1" aria-label="Default select example">
                                            <option selected disabled>@lang('Select Code')</option>
                                            @foreach(config('country') as $value)
                                                <option value="{{$value['phone_code']}}"
                                                        data-name="{{$value['name']}}"
                                                        data-code="{{$value['code']}}"
                                                    {{$country_code == $value['code'] ? 'selected' : ''}}> {{$value['name']}}
                                                    ({{$value['phone_code']}})
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="phone" value="{{old('phone')}}"
                                               class="form-control dialcode-set"
                                               placeholder=""/>
                                    </div>
                                    @error('phone')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <input type="hidden" name="country_code" value="{{old('country_code')}}"
                                       class="text-dark">

                                <div class="input-box col-md-6">
                                    <input type="password" name="password"
                                           class="form-control" placeholder="@lang('Password')"/>
                                    @error('password')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <input type="password" name="password_confirmation"
                                           class="form-control"
                                           placeholder="@lang('Confirm Password')"/>
                                </div>
                            </div>

                            @if(basicControl()->reCaptcha_status_registration)
                                <div class="col-12 input-box mt-5">
                                    {!! NoCaptcha::renderJs(session()->get('trans')) !!}
                                    {!! NoCaptcha::display([]) !!}
                                    @error('g-recaptcha-response')
                                    <span class="text-danger mt-1">@lang($message)</span>
                                    @enderror
                                </div>
                            @endif


                            <button type="submit" class="btn-custom w-100 mt-4">@lang('sign up')</button>
                            <div class="bottom">
                                @lang('Already have an account?')

                                <a href="{{route('login')}}">@lang('Login here')</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            setDialCode();
            $(document).on('change', '.dialCode-change', function () {
                setDialCode();
            });

            function setDialCode() {
                let currency = $('.dialCode-change').val();
                $('.dialcode-set').val(currency);
            }

        });

    </script>
@endpush
