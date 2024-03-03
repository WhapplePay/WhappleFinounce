@extends('admin.layouts.app')
@section('title')
    @lang($user->username)
@endsection
@section('content')

    <div class="m-0 m-md-4 my-4 m-md-0">
        <div class="row">

            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">@lang('Profile')</h4>
                        <div class="form-group">
                            <div class="image-input">
                                <img id="image_preview_container" class="preview-image"
                                     src="{{getFile(config('location.user.path').$user->image) }}"
                                     alt="preview image">
                            </div>
                        </div>
                        <h3> @lang(ucfirst($user->username))</h3>
                        <p>@lang('Joined At') @lang($user->created_at->format('d M,Y h:i A')) </p>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">@lang('User information')</h4>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Email')
                                <span>{{ $user->email }}</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Username')
                                <span>{{ $user->username }}</span></li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Status')
                                <span
                                    class="badge badge-{{($user->status==1) ? 'success' :'danger'}} success badge-pill">{{($user->status==1) ? trans('Active') : trans('Inactive')}}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Complete Trades')
                                <span class="badge badge-success">{{$user->completed_trade}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Avg Speed')
                                @if($user->completed_trade>0)
                                    <span>{{$user->total_min/$user->completed_trade}} @lang('Minutes') </span>
                                @else
                                    <span>0 @lang('Minutes') </span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">@lang('User action')</h4>


                        <div class="btn-list ">
                                <button class="btn btn-primary btn-sm" type="button" data-toggle="modal"
                                        data-target="#balance">
                                    <span class="btn-label"><i class="fas fa-hand-holding-usd"></i></span>
                                    @lang('Add/Subtract Fund')
                                </button>

                                <a href="{{ route('admin.user.transaction',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-exchange-alt"></i></span> @lang('Transaction Log')
                                </a>


                                <a href="{{ route('admin.payment.log',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-money-bill-alt"></i></span> @lang('Deposit Log')
                                </a>


                                <a href="{{ route('admin.payout-log',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-hand-holding"></i></span> @lang('Withdraw Log')
                                </a>

                                <a href="{{ route('admin.send-email',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-envelope-open"></i></span> @lang('Send Email')
                                </a>


                                <a href="{{ route('admin.user.userKycHistory',$user) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-file-invoice"></i></span> @lang('KYC Records')
                                </a>


                        </div>


                    </div>
                </div>

            </div>

            <div class="col-md-8">

                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">{{ ucfirst($user->username) }} @lang('Information')</h4>
                        <form method="post" action="{{ route('admin.user-update', $user->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('First Name')</label>
                                        <input class="form-control" type="text" name="firstname"
                                               value="{{ $user->firstname }}"
                                               required>
                                        @error('firstname')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Last Name')</label>
                                        <input class="form-control" type="text" name="lastname"
                                               value="{{ $user->lastname }}"
                                               required>
                                        @error('lastname')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Username')</label>
                                        <input class="form-control" type="text" name="username"
                                               value="{{ $user->username }}" required>
                                        @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Email')</label>
                                        <input class="form-control" type="email" name="email" value="{{ $user->email }}"
                                               required>
                                        @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Phone Number')</label>
                                        <input class="form-control" type="text" name="phone" value="{{ $user->phone }}">
                                        @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group ">
                                        <label>@lang('Preferred language')</label>

                                        <select name="language_id" id="language_id" class="form-control">
                                            <option value="" disabled>@lang('Select Language')</option>
                                            @foreach($languages as $la)
                                                <option value="{{$la->id}}"
                                                    {{ old('language_id', $user->language_id) == $la->id ? 'selected' : '' }}>@lang($la->name)</option>
                                            @endforeach
                                        </select>

                                        @error('language_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Address')</label>
                                        <textarea class="form-control" name="address"
                                                  rows="2">{{ $user->address }}</textarea>
                                        @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Incomplete Trade Limit For Ad')</label>
                                        <input type="number" class="form-control"
                                               name="trade_limit"
                                               value="{{$user->trade_limit ?? config('basic.incompleteLimit')}}"/>
                                        @error('trade_limit')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>@lang('Status')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='status'>
                                                <input type="checkbox" name="status" class="custom-switch-checkbox"
                                                       id="status" {{ $user->status == 0 ? 'checked' : '' }} >
                                                <label class="custom-switch-checkbox-label" for="status">
                                                    <span class="status custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <label>@lang('Email Verification')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='email_verification'>
                                                <input type="checkbox" name="email_verification"
                                                       class="custom-switch-checkbox"
                                                       id="email_verification" {{ $user->email_verification == 0 ? 'checked' : '' }}>
                                                <label class="custom-switch-checkbox-label" for="email_verification">
                                                    <span class="verify custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label>@lang('SMS Verification')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='sms_verification'>
                                                <input type="checkbox" name="sms_verification"
                                                       class="custom-switch-checkbox"
                                                       id="sms_verification" {{ $user->sms_verification == 0 ? 'checked' : '' }}>
                                                <label class="custom-switch-checkbox-label" for="sms_verification">
                                                    <span class="verify custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label>@lang('2FA Secturity')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='0' name='two_fa_verify'>
                                                <input type="checkbox" name="two_fa_verify"
                                                       class="custom-switch-checkbox"
                                                       id="two_fa_verify" {{ $user->two_fa_verify == 1 ? 'checked' : '' }}>
                                                <label class="custom-switch-checkbox-label" for="two_fa_verify">
                                                    <span class="custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn-wrapper mt-md-3  text-center text-md-left">
                                <button type="submit"
                                        class=" btn waves-effect waves-light btn-rounded btn-primary btn-block">
                                    <span>@lang('Update User')</span></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    @forelse($wallets as $wallet)
                        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                            <div class="card shadow border-right">
                                <div class="card-body">
                                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                                        <div>
                                            <div class="d-inline-flex align-items-center">
                                                <h2 class="text-dark mb-1 font-weight-medium">{{$wallet->balance}}</h2>
                                            </div>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">{{optional($wallet->crypto)->code}}</h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0">
                                            <span class="opacity-7 text-muted"><img width="30" height="30"
                                                                                    src="{{getFile(config('location.currency.path').optional($wallet->crypto)->image)}}"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">@lang('Password Change')</h4>

                        <form method="post" action="{{ route('admin.userPasswordUpdate',$user->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label>@lang('New Password')</label>
                                        <input id="new_password" type="password" class="form-control" name="password"
                                               autocomplete="current-password">
                                        @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group ">
                                        <label>@lang('Confirm Password')</label>
                                        <input id="confirm_password" type="password" name="password_confirmation"
                                               autocomplete="current-password" class="form-control">
                                        @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn-wrapper mt-md-3 text-center text-md-left">
                                <button type="submit"
                                        class="btn waves-effect waves-light btn-rounded btn-primary btn-block">
                                    <span>@lang('Update Password')</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal fade" id="balance">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" action="{{ route('admin.user-balance-update',$user->id) }}"
                      enctype="multipart/form-data">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title">@lang('Add / Subtract Balance')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="form-group ">
                            <label>@lang('Select Wallet')</label>
                            <select name="wallet" class="form-control wallet">
                                <option value="" disabled selected>@lang('Select One')</option>
                                @foreach($wallets as $wallet)
                                    <option value="{{$wallet->id}}" data-code="{{optional($wallet->crypto)->code}}"
                                            @if(@request()->wallet == $wallet->id) selected @endif>
                                        {{optional($wallet->crypto)->code}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="balance" id="balance">
                                <div class="input-group-append">
                                    <span class="form-control" id="code"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-switch-btn w-md-100">
                                <input type='hidden' value='1' name='add_status'>
                                <input type="checkbox" name="add_status" class=" custom-switch-checkbox" id="add_status"
                                       value="0">
                                <label class="custom-switch-checkbox-label" for="add_status">
                                    <span class="modal_status custom-switch-checkbox-inner"></span>
                                    <span class="custom-switch-checkbox-switch"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class=" btn btn-primary balanceSave"><span>@lang('Submit')</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <style>
        .modal_status.custom-switch-checkbox-inner:before {
            content: "+ Add Balance";
        }

        .modal_status.custom-switch-checkbox-inner:after {
            content: "- Substruct Balance";
        }

        .status.custom-switch-checkbox-inner:before {
            content: "Active";
        }

        .status.custom-switch-checkbox-inner:after {
            content: "Banned";
        }

        .verify.custom-switch-checkbox-inner:before {
            content: "Verfied";
        }

        .verify.custom-switch-checkbox-inner:after {
            content: "Unverfied";
        }

    </style>
@endpush

@push('js')
    <script>
        "use strict";
        $(document).ready(function () {
            $(document).on('click', '#image-label', function () {
                $('#image').trigger('click');
            });
            $(document).on('change', '#image', function () {
                var _this = $(this);
                var newimage = new FileReader();
                newimage.readAsDataURL(this.files[0]);
                newimage.onload = function (e) {
                    $('#image_preview_container').attr('src', e.target.result);
                }
            });
            $(document).on('click', '.balanceSave', function () {
                var bala = $('#balance').text();
            });

            $(document).on('change', '.wallet', function () {
                var code = $(this).select2().find(":selected").data("code");
                $('#code').text(code);
            });

            $('select').select2({
                selectOnClose: true,
                width: "100%"
            });
        });
    </script>
@endpush


