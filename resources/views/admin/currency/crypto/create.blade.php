@extends('admin.layouts.app')
@section('title')
    @lang('Create Crypto Currency')
@endsection
@section('content')

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="media mb-4 justify-content-end">
                <a href="{{route('admin.listCrypto')}}" class="btn btn-sm  btn-primary mr-2">
                    <span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
                </a>
            </div>

            <form action="{{route('admin.storeCrypto')}}" class="form-row justify-content-center" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="col-md-8">
                    <div class="row ">
                        <div class=" col-md-6">
                            <div class="form-group">
                                <label>@lang('Name')</label>
                                <input type="text" name="name" value="{{old('name')}}"
                                       placeholder="@lang('eg. Bitcoin, Lightcoin')" class="form-control" required>
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class=" col-md-6">
                            <div class="form-group">
                                <label>@lang('Code')</label>
                                <input type="text" name="code" value="{{old('code')}}"
                                       placeholder="@lang('eg. BTC, LTC')" class="form-control" required>
                                @error('code')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class=" col-md-6">
                            <div class="form-group">
                                <label>@lang('Symbol')</label>
                                <input type="text" name="symbol" value="{{old('symbol')}}"
                                       placeholder="@lang('eg. B, L')" class="form-control" required>
                                @error('symbol')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>@lang('Rate')</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <span
                                            class="currencySign">1 </span> = </span>
                                </div>
                                <input type="text" name="rate" value="{{old('rate')}}" class="form-control"
                                       placeholder="0" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">@lang('USD')</span>
                                </div>
                            </div>
                            @error('rate')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="name"> @lang('Deposit Charges') </label>
                            <div class="input-group">
                                <input type="text" name="deposit_charge"
                                       class="form-control"
                                       value="{{ old('deposit_charge') }}">
                                <div class="input-group-append">
                                    <select class="form-control  mb-3" name="deposit_type"
                                            aria-label=".form-select-lg example">
                                        <option value="1"
                                                class="minMaxCurrency">@lang(config('basic.currency'))</option>
                                        <option value="0">%</option>
                                    </select>
                                </div>
                                @error('deposit_charge')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="name"> @lang('Withdrawal Charges') </label>
                            <div class="input-group">
                                <input type="text" name="withdraw_charge"
                                       class="form-control"
                                       value="{{ old('withdraw_charge') }}">
                                <div class="input-group-append">
                                    <select class="form-control  mb-3" name="withdraw_type"
                                            aria-label=".form-select-lg example">
                                        <option value="1"
                                                class="minMaxCurrency">@lang(config('basic.currency'))</option>
                                        <option value="0">%</option>
                                    </select>
                                </div>
                                @error('withdraw_charge')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">@lang('Image')</label>
                                <div class="image-input ">
                                    <label for="image-upload" id="image-label"><i
                                            class="fas fa-upload"></i></label>
                                    <input type="file" name="image" placeholder="@lang('Choose image')"
                                           id="image" required>
                                    <img id="image_preview_container" class="preview-image"
                                         src="{{ getFile(config('location.category.path')) }}"
                                         alt="@lang('preview image')">
                                </div>
                                <span
                                    class="text-secondary">@lang('Image size') {{config('location.currency.size')}} @lang('px')</span>
                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>@lang('Status')</label>
                                    <input data-toggle="toggle" id="status" data-onstyle="success"
                                           data-offstyle="info" data-on="Active" data-off="Deactive" data-width="100%"
                                           type="checkbox" checked name="status">
                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                                class="fas fa-save pr-2"></i> @lang('Save')</span></button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote.min.css') }}">
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote.min.js') }}"></script>
@endpush

@push('js')
    <script>
        "use strict";
        $(document).on('keyup', 'input[name=code]', function (e) {
            $('.currencySign').text("1 " + $(this).val());
            $('.minMaxCurrency').text($(this).val());
        })

        $('#image').on('change', function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#image_preview_container').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

    </script>
@endpush
