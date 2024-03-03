<!-- buy sell -->
@if($buy)
    <section class="buy-sell">
        <div class="container">
            @if(isset($templates['buy-sell'][0]) && $buy_sell = $templates['buy-sell'][0])
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5>@lang(optional($buy_sell->description)->title)</h5>
                            <h2>@lang(optional($buy_sell->description)->sub_title)</h2>
                            <p class="mx-auto">
                                @lang(optional($buy_sell->description)->short_description)
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col">
                    <div
                        class="table-parent table-responsive"
                        data-aos="fade-up"
                        data-aos-duration="1000"
                        data-aos-anchor-placement="center-bottom">
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
                                    <td data-label="@lang('Payment Method')">@foreach($item->gateways as $gateway)
                                            <span class="gateway-color"
                                                  style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                        @endforeach</td>
                                    <td data-label="@lang('Rate')">@lang(number_format($item->rate,3)) {{optional($item->fiatCurrency)->code}}
                                        /{{optional($item->cryptoCurrency)->code}}</td>
                                    <td data-label="@lang('Limit')">{{$item->minimum_limit}}
                                        - {{$item->maximum_limit}} {{optional($item->fiatCurrency)->code}}</td>
                                    <td data-label="@lang('Payment Window')">{{optional($item->paymentWindow)->name}}</td>
                                    <td data-label="@lang('Action')" class="action">
                                        <a href="{{route('user.buyCurrencies.tradeRqst',$item->id)}}">
                                            <button class="btn-custom">
                                            @lang('Buy')</a>
                                        </button>
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
                </div>
            </div>
        </div>
    </section>
@endif
