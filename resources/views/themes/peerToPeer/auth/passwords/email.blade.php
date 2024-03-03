@extends($theme.'layouts.app')
@section('title','Reset Password')
@section('content')
    <section class="login-section">
        <div class="container h-100">
            <div class="row h-100 justify-content-center">
                <div class="col-lg-6">
                    <div class="form-wrapper d-flex align-items-center h-100">
                        <form action="{{ route('password.email') }}" method="post">
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
                                    <h5>@lang('Recover Password')</h5>
                                </div>
                                <div class="input-box col-12">
                                    <input type="text" name="email" value="{{old('email')}}" class="form-control"
                                           placeholder="@lang('Email address')"/>
                                    @error('email')<span class="text-danger  mt-1">{{ trans($message) }}</span>@enderror
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

