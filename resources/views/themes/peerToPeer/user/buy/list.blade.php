@extends($theme.'layouts.user')
@section('title')
    @lang('Buy')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <h5>@lang('Buy') - {{$currencyCode ? strtoupper($currencyCode):'Crypto'}}</h5>
                <div class="tab-section">
                    <div class="switcher navigator">
                        <a href="{{route('user.buyCurrencies.list','all')}}"
                           class="buy tab text-uppercase {{(last(request()->segments())=='all')?'active':''}}">@lang('All')</a>
                        @forelse($buyCurrencyLists as $item)
                            <a href="{{route('user.buyCurrencies.list',[@slug(optional($item->cryptoCurrency)->code),$item->crypto_id])}}"
                               class="buy tab text-uppercase {{(last(request()->segments())==$item->crypto_id)?'active':''}}">{{optional($item->cryptoCurrency)->code}}</a>
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="search-bar">
                    <form action="{{route('user.buyCurrencies.list')}}" method="get">
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
            </div>
        </div>
        <div class="table-parent table-responsive">
            <table class="table table-striped" id="service-table">
                <thead>
                <tr>
                    <th>@lang('SL No.')</th>
                    <th>@lang('Seller')</th>
                    <th>@lang('Payment method')</th>
                    <th>@lang('Rate')</th>
                    <th>@lang('Payment Window')</th>
                    <th>@lang('Limit')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($buyLists as $key => $item)
                    <tr>
                        <td data-label="@lang('SL No.')">{{++$key}}</td>
                        <td data-label="@lang('Seller')">
                            <a href="{{route('user.profile.page',$item->user_id)}}">
                                <div class="d-lg-flex d-block align-items-center">
                                    <div class="me-3"><img
                                            src="{{getFile(config('location.user.path').optional($item->user)->image) }}"
                                            alt="user" class="rounded-circle" width="45"
                                            height="45">
                                        <i class="tb-online position-absolute fa fa-circle text-{{(optional($item->user)->lastSeen == true) ?trans('success'):trans('danger') }}"
                                           title="{{(optional($item->user)->lastSeen == true) ?trans('Online'):trans('Away') }}"></i>
                                    </div>
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
                                    <span class="gateway-color"
                                          style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                @endforeach
                            </div>
                        </td>
                        <td data-label="@lang('Rate')">@lang(number_format($item->rate,2)) {{optional($item->fiatCurrency)->code}}
                            /{{optional($item->cryptoCurrency)->code}}</td>
                        <td data-label="@lang('Payment Window')">@lang(optional($item->paymentWindow)->name)</td>
                        <td data-label="@lang('Limit')">{{$item->minimum_limit}} {{optional($item->fiatCurrency)->code}}
                            - {{$item->maximum_limit}}  {{optional($item->fiatCurrency)->code}}</td>
                        <td data-label="@lang('Action')" class="action">
                            <a href="{{route('user.buyCurrencies.tradeRqst',$item->id)}}"
                               class="btn-custom buy-currency p-6">
                                @lang('Buy '){{$item->cryptoCurrency->code}}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <div class="no-data-message">
                                <span class="icon-wrapper">
                                    <span class="file-icon">
                                        <i class="fas fa-file-times"></i>
                                    </span>
                                </span>
                                <p class="message">@lang('No data found')</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{ $buyLists->appends($_GET)->links($theme.'partials.pagination') }}
            </ul>
        </nav>
    </div>
@endsection
