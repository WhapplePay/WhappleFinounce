@extends('admin.layouts.app')
@section('title')
    @lang('Api Setting')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="text-dark">@lang('CoinPayment Setting')</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.api-setting.update')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class=" col-md-6">
                                <div class="form-group">
                                    <label>@lang('Public Key') <sup class="text-danger font-16">*</sup></label>
                                    <input type="text" name="public_key" value="{{$configure->public_key??'null'}}"
                                           placeholder="" class="form-control">
                                    @error('public_key')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class=" col-md-6">
                                <div class="form-group">
                                    <label>@lang('Private Key') <sup class="text-danger font-16">*</sup></label>
                                    <input type="text" name="private_key" value="{{$configure->private_key??'null'}}"
                                           placeholder="" class="form-control">
                                    @error('private_key')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" col-md-6">
                                <div class="form-group">
                                    <label>@lang('Merchant ID') <sup class="text-danger font-16">*</sup></label>
                                    <input type="text" name="merchant_id" value="{{$configure->merchant_id??'null'}}"
                                           placeholder="" class="form-control">
                                    @error('merchant_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                                class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                                    class="fas fa-save pr-2"></i> @lang('Update')</span></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div
                    class="card-header bg-primary text-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">@lang('Instructions')</h6>
                </div>
                <div class="card-body">
                    @lang('Using CoinPayments, merchants can accept crypto payments via APIs and a mobile POS system, and choose to either hold the funds in cryptocurrency or have them automatically converted into fiat currency and sent into a company bank account
                    <br><br>
                    Get your free API keys')
                    <a href="https://www.coinpayments.net/register" target="_blank">@lang('Create an account')
                        <i class="fas fa-external-link-alt"></i></a>
                    @lang(', then create a account.
                    Go to the panel page for public key, Private key and Merchant ID.')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        'use strict'

        $(document).on('click', '.copy-btn', function () {
            var _this = $(this)[0];
            var copyText = $(this).parents('.input-group-append').siblings('input');
            $(copyText).prop('disabled', false);
            copyText.select();
            document.execCommand("copy");
            $(copyText).prop('disabled', true);
            $(this).text('Coppied');
            setTimeout(function () {
                $(_this).text('');
                $(_this).html('<i class="fas fa-copy"></i>');
            }, 500)
        });
    </script>
@endpush
