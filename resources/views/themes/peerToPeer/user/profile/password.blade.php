@extends($theme.'user.setting.index')
@section('dynamic')
    <div class="col-lg-4">
        <div class="side-bar">
            <!-- edit profile section -->
            <div class="identity-confirmation">
                <form action="{{ route('user.updatePassword')}}" class="mt-0" method="post">
                    @csrf
                    <div class="row g-4">
                        <div class="col-12">
                            <h4>@lang('Update Password')</h4>
                        </div>
                        <div class="input-box col-12">
                            <label for="">@lang('Current Password')</label>
                            <input class="form-control" id="password" type="password"
                                   name="current_password"
                                   autocomplete="off"/>
                            @if($errors->has('current_password'))
                                <div
                                    class="error text-danger">@lang($errors->first('current_password')) </div>
                            @endif
                        </div>
                        <div class="input-box col-12">
                            <label for="">@lang('New Password')</label>
                            <input class="form-control" id="password" type="password" name="password"
                                   autocomplete="off"/>
                            @if($errors->has('password'))
                                <div
                                    class="error text-danger">@lang($errors->first('password')) </div>
                            @endif
                        </div>
                        <div class="input-box col-12">
                            <label for="">@lang('Confirm Password')</label>
                            <input class="form-control" id="password_confirmation" type="password"
                                   name="password_confirmation"
                                   autocomplete="off"/>
                            @if($errors->has('password_confirmation'))
                                <div
                                    class="error text-danger">@lang($errors->first('password_confirmation')) </div>
                            @endif
                        </div>
                        <div class="input-box col-12">
                            <button type="submit" class="btn-custom">@lang('Update Password')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
