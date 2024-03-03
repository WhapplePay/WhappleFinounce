@extends($theme.'layouts.user')
@section('title')
    @lang('Update')
@endsection
@section('content')
    <div class="container-fluid" id="ad-app">
        <div class="main row">
            <section class="edit-profile-section">
                <div class="row">
                    <div class="col-12">
                        <h4 class="">@lang('Update Advertisement')</h4>
                        <form action="{{route('user.advertisements.update',$advertise->id)}}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="input-box col-md-6">
                                    <label for="type">@lang('I Want To')
                                        <i class="fas fa-info-circle ms-2 theme-color"
                                           data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="@lang('What kind of advertisement do you wish to create? If you wish to sell bitcoins make sure you have bitcoins in your wallet.')"></i>
                                    </label>
                                    <select class="form-select tradeType" aria-label="Default select example"
                                            name="type" required>
                                        <option selected disabled>@lang('Select One')</option>
                                        <option
                                            value="sell" {{$advertise->type == 'sell' ?'selected':''}} >@lang('Sell')</option>
                                        <option
                                            value="buy" {{$advertise->type == 'buy' ?'selected':''}}>@lang('Buy')</option>
                                    </select>
                                    <p class="mt-1 theme-color" id="info"></p>
                                    @error('type')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="crypto_id">@lang('Cryptocurrency') <i class="fas fa-info-circle ms-2 theme-color"

                                                                             data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="@lang('Which cryptocurrency do you wish to buy or sell?')"></i></label>
                                    <select class="form-select" aria-label="Default select example" name="crypto_id"
                                            id="cryptoChange" required>
                                        <option selected disabled>@lang('Select One')</option>
                                        @forelse($cryptos as $crypto)
                                            <option
                                                value="{{$crypto->id}}"
                                                {{$advertise->crypto_id == $crypto->id ?'selected':''}} data-code="{{$crypto->code}}"
                                                data-rate="{{$crypto->rate}}">{{$crypto->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('crypto_id')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-md-4">
                                    <label for="">@lang('Payment Method') <i class="fas fa-info-circle ms-2 theme-color"
                                                                             data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="@lang('Which payment method will be used to pay the fiat currency?')"></i></label><br>
                                    <select class="selectpicker" multiple name="gateway_id[]" required>
                                        @forelse($gateways as $gateway)
                                            <option
                                                value="{{$gateway->id}}" @foreach($advertise->gateways as $item)
                                                {{($item->id ==$gateway->id)?'selected':'' }}
                                                @endforeach>{{$gateway->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('gateway_id')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-md-4">
                                    <label for="">@lang('Currency') <i class="fas fa-info-circle ms-2 theme-color"
                                                                       data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="@lang('Which fiat currency needs exchange from cryptocurrency?')"></i></label>
                                    <select class="form-select " aria-label="Default select example" id="fiatChange"
                                            name="fiat_id" required>
                                        <option selected disabled>@lang('Select One')</option>
                                        @foreach($fiats as $fiat)
                                            <option
                                                value="{{$fiat->id}}"
                                                {{$advertise->fiat_id == $fiat->id ?'selected':''}} data-code="{{$fiat->code}}"
                                                data-rate="{{$fiat->rate}}">{{$fiat->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('fiat_id')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-md-4">
                                    <label for="">@lang('Price Type')</label>
                                    <select class="form-select priceType" aria-label="Default select example"
                                            name="price_type" required>
                                        <option selected
                                                value="margin" {{$advertise->price_type == 'margin' ?'selected':''}}>@lang('Margin')</option>
                                        <option
                                            value="fixed" {{$advertise->price_type == 'fixed' ?'selected':''}}>@lang('Fixed')</option>
                                    </select>
                                    @error('price_type')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="" class="marginLabel">{{$advertise->price_type}}</label>
                                    <div class="input-group">
                                        <input class="form-control inputPrice" type="number" step="0.001" min="0" name="price"
                                               value="{{$advertise->price}}" required/>
                                        <div class="input-group-prepend">
                                            <span
                                                class="form-control theme-color marginCode">{{$advertise->price_type == 'margin'?'%':optional($advertise->fiatCurrency)->code}}</span>
                                        </div>
                                    </div>
                                    @error('price')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="">@lang('Payment Window') <i class="fas fa-info-circle ms-2 theme-color"
                                                                             data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="@lang('For Buyer: Within how many minutes do you promise to initiate the payment. For Seller: Within how many minutes you want to get paid')"></i></label>
                                    <select class="form-select" aria-label="Default select example"
                                            name="payment_window_id" required>
                                        <option selected disabled>@lang('Select One')</option>
                                        @forelse($paymentWindows as $paymentWindow)
                                            <option
                                                value="{{$paymentWindow->id}}" {{$advertise->payment_window_id == $paymentWindow->id ?'selected':''}}>{{$paymentWindow->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('payment_window_id')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="">@lang('Minimum Limit') <i class="fas fa-info-circle ms-2 theme-color"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="@lang('Minimum transaction limit in one trade.?')"></i></label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" step="0.001" min="0" name="minimum_limit"
                                               value="{{$advertise->minimum_limit}}" required/>
                                        <div class="input-group-prepend">
                                            <span
                                                class="form-control theme-color currencyCode">{{optional($advertise->fiatCurrency)->code}}</span>
                                        </div>
                                    </div>
                                    @error('minimum_limit')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="">@lang('Maximum Limit') <i class="fas fa-info-circle ms-2 theme-color"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="@lang('Maximum transaction limit in one trade.?')"></i></label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" step="0.001" min="0" name="maximum_limit"
                                               value="{{$advertise->maximum_limit}}" required/>
                                        <div class="input-group-prepend">
                                            <span
                                                class="form-control theme-color currencyCode">{{optional($advertise->fiatCurrency)->code}}</span>
                                        </div>
                                    </div>
                                    @error('maximum_limit')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-md-12">
                                    <label for="">@lang('Price Equation:')</label>
                                    <label for=""
                                           class="theme-color priceEquation">{{number_format($advertise->rate,3)}}</label>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="">@lang('Payment Details')</label>
                                    <textarea class="form-control" id="textAreaExample" name="payment_details"
                                              rows="4">{{$advertise->payment_details}}</textarea>
                                    @error('payment_details')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="">@lang('Terms of Trade')</label>
                                    <textarea class="form-control" id="textAreaExample" rows="4"
                                              name="terms_of_trade">{{$advertise->terms_of_trade}}</textarea>
                                    @error('terms_of_trade')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="input-box col-12">
                                    <button type="submit" class="btn-custom">@lang('Update')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('script')
    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp
        <script>
            "use strict";
            @foreach ($errors as $error)
            Notiflix.Notify.Failure("{{trans($error)}}");
            @endforeach
        </script>
    @endif
    <script>
        'use script'

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        $('.selectpicker').select2({
            width: '100%'
        });
        var tradeCharge = {{config('basic.tradeCharge')}}
        $(document).on('change', '.priceType', function (e) {
            if ($(this).val() == 'fixed') {
                $('.marginLabel').text('Fixed');
                $('.marginCode').text($('#fiatChange').find('option:selected').data('code'))
                $('.priceEquation').text('');
                $('.inputPrice').val(0);
            } else {
                $('.marginLabel').text('Margin');
                $('.marginCode').text('%');
            }
        });
        $(document).on('change', '.tradeType', function (e) {
            if ($(this).val() == 'sell') {
                $('#info').text(`For selling ${tradeCharge}% will be charged for each completed trade.`);
            } else {
                $('#info').text('');
            }
        });
        $(document).on('change', '#cryptoChange', function (e) {
            calPrice();
        });
        $(document).on('change', '#fiatChange', function (e) {
            $('.currencyCode').text($(this).find(':selected').data('code'))
            calPrice();
        });
        $(document).on('keyup', '.inputPrice', function (e) {
            calPrice($(this).val());
        });

        function calPrice(inputPrice = null) {
            var fiat = $('#fiatChange').find('option:selected').data('code');
            var crypto = $('#cryptoChange').find('option:selected').data('code');
            var fiatRate = $('#fiatChange').find('option:selected').data('rate');
            var cryptoRate = $('#cryptoChange').find('option:selected').data('rate');
            var priceType = $('.priceType').find('option:selected').val();

            var totalPrice = (parseFloat(fiatRate) * parseFloat(cryptoRate)).toFixed(2);
            var tradePrice = totalPrice;
            if (inputPrice) {
                if (priceType == 'margin') {
                    var tradePrice = ((parseFloat(totalPrice) * parseFloat(inputPrice) / 100) + parseFloat(totalPrice)).toFixed(2)
                } else {
                    var tradePrice = inputPrice;
                }
            }

            if (tradePrice != 'NaN') {
                var priceEquation = `${tradePrice} ${fiat}/${crypto}`
                $('.priceEquation').text(priceEquation);
            }
        }
    </script>
@endpush
