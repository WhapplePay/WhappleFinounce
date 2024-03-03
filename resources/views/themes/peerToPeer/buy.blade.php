@extends($theme.'layouts.app')
@section('title', trans('Buy'))
@section('content')
    <!-- buy sell -->
    <section class="buy-sell">
        <div class="container">
            <div class="search-bar">
                <form action="{{route('buy')}}" method="get">
                    <div class="row g-3 align-items-end">
                        <div class="input-box col-lg-2">
                            <label for="">@lang('Username or Email')</label>
                            <div class="form-group">
                                <input type="text" name="seller"
                                       value="{{@request()->seller}}"
                                       class="form-control" placeholder="@lang('Username or Email')">
                            </div>
                        </div>
                        <div class="input-box col-lg-2">
                            <label for="">@lang('Crypto Currency')</label>
                            <select class="form-select" aria-label="Default select example" name="crypto">
                                <option value="">@lang('Select Crypto')</option>
                                @forelse($cryptos as $crypto)
                                    <option
                                        value="{{$crypto->id}}" {{@request()->crypto == $crypto->id?'selected':''}}>
                                        {{$crypto->code}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="input-box col-lg-2">
                            <label for="">@lang('Fiat Currency')</label>
                            <select class="form-select" aria-label="Default select example" name="fiat">
                                <option value="">@lang('Select Fiat')</option>
                                @forelse($fiats as $fiat)
                                    <option
                                        value="{{$fiat->id}}" {{@request()->fiat == $fiat->id?'selected':''}}>{{$fiat->code}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="input-box col-lg-2">
                            <label for="">@lang('Payment Method')</label>
                            <select class="form-select" aria-label="Default select example" name="gateway">
                                <option value="">@lang('Select Method')</option>
                                @forelse($gateways as $gateway)
                                    <option
                                        value="{{$gateway->id}}" {{@request()->gateway == $gateway->id?'selected':''}}>{{$gateway->name}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>

                        <div class="input-box col-lg-2">
                            <label for="">@lang('Offer Location')</label>
                            <select class="form-select" aria-label="Default select example" name="location">
                                <option value="">@lang('Select Location')</option>
                                @forelse($locations as $location)
                                    <option
                                        value="{{$location->address}}" {{@request()->location == $location->address?'selected':''}}>{{ucfirst($location->address)}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>

                        <div class="input-box col-lg-2">
                            <div class="form-group">
                                <button type="submit" class="btn-custom w-100">
                                    <i class="fas fa-search"></i> @lang('Search')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-parent table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>@lang('Seller')</th>
                                <th>@lang('Payment Method')</th>
                                <th>@lang('Rate')</th>
                                <th>@lang('Limit')</th>
                                <th>@lang('Payment Window')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($buy as $item)
                                <tr>
                                    <td data-label="@lang('Seller')">
                                        <a href="{{route('user.profile.page',$item->user_id)}}">
                                            <div class="d-lg-flex d-block align-items-center">
                                                <div class="me-3"><img
                                                        src="{{getFile(config('location.user.path').optional($item->user)->image) }}"
                                                        alt="user" class="rounded-circle" width="45"
                                                        height="45"></div>
                                                <div class="">
                                                    <h6 class="text-white mb-0 text-lowercase">@lang(optional($item->user)->username)</h6>
                                                    <span
                                                        class="text-muted font-10">{{optional($item->user)->total_trade}} @lang('Trades') |</span>
                                                    @if(optional($item->user)->total_trade > 0)
                                                        <span
                                                            class="text-muted font-10">@lang('Completion') {{number_format(optional($item->user)->completed_trade*100/optional($item->user)->total_trade,2)}}
                                                %</span>
                                                    @else
                                                        <span
                                                            class="text-muted font-10">@lang('Completion') 0
                                                %</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td data-label="@lang('Payment Method')">
                                        <div class="d-flex flex-wrap">
                                            @foreach($item->gateways as $gateway)
                                                <a href="{{route('buy',$gateway->id)}}"><span class="gateway-color"
                                                                                               style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span></a>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td data-label="@lang('Rate')">@lang(number_format($item->rate,3)) {{optional($item->fiatCurrency)->code}}
                                        /{{optional($item->cryptoCurrency)->code}}</td>
                                    <td data-label="@lang('Limit')">{{$item->minimum_limit}}
                                        - {{$item->maximum_limit}} {{optional($item->fiatCurrency)->code}}</td>
                                    <td data-label="@lang('Payment Window')">{{optional($item->paymentWindow)->name}}</td>
                                    <td data-label="@lang('Action')" class="action">
                                        <a href="{{route('user.buyCurrencies.tradeRqst',$item->id)}}"
                                           class="btn-custom p-6">
                                            @lang('Buy')</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%">
                                        <div class="no-data-message"><span class="icon-wrapper">
                                                 <span class="file-icon"><i class="fas fa-file-times"></i></span></span>
                                            <p class="message">@lang('No data found')</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $buy->appends($_GET)->links($theme.'partials.pagination') }}
                </div>
            </div>
        </div>
    </section>
@endsection
