@extends($theme.'layouts.user')
@section('title')
    @lang('Profile')
@endsection
@section('content')
    <!-- user profile -->
    <div class="container-fluid">
        <div class="main row">
            <section class="user-profile">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="sidebar-wrapper">
                            <div class="profile">
                                <div class="img">
                                    <img id="profile"
                                         src="{{asset(getFile(config('location.user.path').$user->image))}}" alt="..."
                                         class="img-fluid"/>
                                    <i class="profile-online position-absolute fa fa-circle text-{{($user->lastSeen == true) ?trans('success'):trans('warning') }}"
                                       title="{{($user->lastSeen == true) ?trans('Online'):trans('Away') }}"></i>
                                </div>
                                <div>
                                    <h5 class="name">
                                        {{$user->fullname}}
                                        @if($user->identity_verify == 2)
                                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                                        @endif
                                    </h5>
                                    <span class="username">{{$user->username}}</span>
                                </div>
                            </div>
                            <ul>
                                <li>@lang('Country:') <span>{{ucfirst($user->address)}}</span></li>
                                <li>@lang('Joined:')
                                    <span>{{\Carbon\Carbon::parse($user->created_at)->diffForHumans()}}</span></li>
                                @if($user->email_verification == 1)
                                    <li>@lang('Email:') <span class="status-success">@lang('verified')</span></li>
                                @else
                                    <li>@lang('Email:') <span class="status-danger">@lang('Unverified')</span></li>
                                @endif

                                @if($user->sms_verification == 1)
                                    <li>@lang('Phone:') <span class="status-success">@lang('verified')</span></li>
                                @else
                                    <li>@lang('Phone:') <span class="status-danger">@lang('Unverified')</span></li>
                                @endif
                                <li>@lang('Complete Trades:') <span
                                        class="badge bg-success">{{$user->completed_trade}}</span></li>
                                <li>@lang('Advertisements:') <span
                                        class="badge bg-dark">{{count($user->advertise)}}</span></li>
                                <li>@lang('Avg Speed:')
                                    @if($user->completed_trade > 0)
                                        <span>{{number_format($user->total_min/$user->completed_trade,0)}} @lang('Minutes')</span>
                                    @else
                                        <span>0 @lang('Minutes')</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div>
                            <h5>@lang('Latest 10 Buy Ads')</h5>
                            <div class="table-parent table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>@lang('Payment Method')</th>
                                        <th>@lang('Rate')</th>
                                        <th>@lang('Limit')</th>
                                        <th>@lang('Payment Window')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($user->advertise->where('type','sell')->sortBy('desc')->take(10) as $item)
                                        <tr>
                                            <td data-label="@lang('Payment Method')">
                                                <div class="d-flex flex-wrap">
                                                    @foreach($item->gateways as $gateway)
                                                    <span class="gateway-color"
                                                          style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
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
                        </div>
                        <div class="mt-5">
                            <h5>@lang('Latest 10 Sell Ads')</h5>
                            <div class="table-parent table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>@lang('Payment Method')</th>
                                        <th>@lang('Rate')</th>
                                        <th>@lang('Limit')</th>
                                        <th>@lang('Payment Window')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($user->advertise->where('type','buy')->take(10) as $item)
                                        <tr>
                                            <td data-label="@lang('Payment Method')">
                                                <div class="d-flex flex-wrap">
                                                    @foreach($item->gateways as $gateway)
                                                    <span class="gateway-color"
                                                          style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                                @endforeach
                                                </div>
                                            </td>
                                            <td data-label="@lang('Rate')">@lang(number_format($item->rate,3)) {{optional($item->fiatCurrency)->code}}
                                                /{{optional($item->cryptoCurrency)->code}}</td>
                                            <td data-label="@lang('Limit')">{{$item->minimum_limit}}
                                                - {{$item->maximum_limit}} {{optional($item->fiatCurrency)->code}}</td>
                                            <td data-label="@lang('Payment Window')">{{optional($item->paymentWindow)->name}}</td>
                                            <td data-label="@lang('Action')" class="action">
                                                <a href="{{route('user.sellCurrencies.tradeRqst',$item->id)}}"
                                                   class="btn-custom p-6">
                                                    @lang('Sell')</a>
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
        </div>
    </div>
@endsection
