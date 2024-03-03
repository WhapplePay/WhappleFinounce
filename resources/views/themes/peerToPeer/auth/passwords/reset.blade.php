@extends($theme.'layouts.app')
@section('title','Reset Password')
@section('content')
    <section class="login-section">
        <div class="container h-100">
            <div class="row h-100 justify-content-center">
                <div class="col-lg-6">
                    <div class="form-wrapper d-flex align-items-center h-100">
                        <form class="login-form" action="{{route('password.update')}}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    @if (session('status'))
                                        <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                                            {{ trans(session('status')) }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close">
                                                <span aria-hidden="true"></span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12">
                                    @error('token')
                                    <div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
                                        {{ trans($message) }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"></span>
                                        </button>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <h5>@lang('Reset Password')</h5>
                                </div>
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ $email }}">

                                <div class="input-box col-12">
                                    <input type="password" name="password" class="form-control"
                                           placeholder="@lang('New Password')"/>
                                    @error('password')<span class="text-danger  mt-1">{{ trans($message) }}</span>@enderror
                                </div>

                                <div class="input-box col-12">
                                    <input type="password" name="password_confirmation" class="form-control"
                                           placeholder="@lang('New Password')"/>
                                    @error('password')<span class="text-danger  mt-1">{{ trans($message) }}</span>@enderror
                                </div>

                                <div class="input-box col-12">
                                    <button type="submit"
                                            class="btn-custom w-100">@lang('Send Password Reset Link')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
