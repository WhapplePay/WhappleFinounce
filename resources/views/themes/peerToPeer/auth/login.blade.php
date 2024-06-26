@extends($theme.'layouts.app')
@section('title','Login')
@section('content')
    <!-- login section -->
    <section class="login-section">
        <div class="container h-100">
            <div class="row h-100 justify-content-center">
                <div class="col-lg-6">
                    <div class="form-wrapper d-flex align-items-center h-100">
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <h5>@lang('Login here')</h5>
                                </div>
                                <div class="input-box col-12">
                                    <input type="text" name="username" value="{{old('username')}}" class="form-control"
                                           placeholder="@lang('Email Or Username')"/>
                                    @error('username')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                    @error('email')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                </div>
                                <div class="input-box col-12">
                                    <input type="password" name="password"  class="form-control" placeholder="Password"/>
                                    @error('password')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if(basicControl()->reCaptcha_status_login)
                                    <div class="col-12 ">
                                        {!! NoCaptcha::renderJs(session()->get('trans')) !!}
                                        {!! NoCaptcha::display([]) !!}
                                        @error('g-recaptcha-response')
                                        <span class="text-danger mt-1">@lang($message)</span>
                                        @enderror
                                    </div>
                                @endif

                                <div class="col-12">
                                    <div class="links">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                   id="flexCheckDefault" name="remember" {{ old('remember') ? 'checked' : '' }}/>
                                            <label class="form-check-label" for="flexCheckDefault"> @lang('Remember me') </label>
                                        </div>
                                        <a href="{{ route('password.request') }}">@lang('Forgot password?')</a>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-custom w-100">@lang('sign in')</button>
                            <div class="bottom">
                                @lang("Don't have an account?")
                                <a href="{{ route('register') }}">@lang('Create account')</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
