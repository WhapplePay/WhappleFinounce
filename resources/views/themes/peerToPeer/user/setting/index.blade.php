@extends($theme.'layouts.user')
@section('title')
    @lang('Settings')
@endsection
@section('content')
    <!-- main -->
    <div class="container-fluid">
        <div class="main row g-4">
            <div class="company-setting-wrapper col-12">
                <div class="row">
                    <div @if(request()->routeIs('user.list.setting')) class="col-lg-12" @else class="col-lg-8" @endif>
                        <div class="settings-wrapper">
                            <h5 class="mb-3">@lang('Settings')</h5>
                            <a href="{{route('user.password')}}">
                                <div class="box {{menuActive(['user.password','user.updatePassword'])}}">
                                    <h5>@lang('Password Setting')</h5>
                                    <p>@lang('This is password setting where you can change password click to continue.')</p>
                                </div>
                            </a>
                            <a href="{{route('user.identityVerify')}}">
                                <div class="box {{menuActive(['user.identityVerify','user.verificationSubmit'])}}">
                                    <h5>@lang('Identity Verification')</h5>
                                    <p>@lang('This is identity verification setting where you can verified your identity click to continue.')</p>
                                </div>
                            </a>
                            <a href="{{route('user.twostep.security')}}">
                                <div
                                    class="box {{menuActive(['user.twostep.security'])}}">
                                    <h5>@lang('2 FA Security')</h5>
                                    <p>@lang('Two Factor Security prevent from unauthorized login action click to continue.')</p>
                                </div>
                            </a>
                            <a href="{{route('user.list.setting.notify')}}">
                                <div
                                    class="box {{menuActive(['user.list.setting.notify','user.update.setting.notify'])}}">
                                    <h5>@lang('Push Notify Setting')</h5>
                                    <p>@lang('This is push notification setting where you can manage your notification action click to continue.')</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    @yield('dynamic')
                </div>
            </div>
        </div>
    </div>
@endsection
