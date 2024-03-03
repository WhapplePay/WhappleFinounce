<!-- sidebar -->
<div id="sidebar" class="">
    <div class="sidebar-top">
        <a class="navbar-brand" href="{{route('home')}}"> <img
                src="{{ getFile(config('location.logoIcon.path') . 'logo.png') }}" alt=""/></a>
        <button class="sidebar-toggler d-md-none" onclick="toggleSideMenu()">
            <i class="fal fa-times"></i>
        </button>
    </div>
    <ul class="main">
        <li>
            <a class="{{menuActive('user.home')}}" href="{{route('user.home')}}"><i
                    class="fal fa-th-large"></i>@lang('Dashboard')</a>
        </li>
        <li>
            <a class="{{menuActive('user.wallet.list')}}" href="{{route('user.wallet.list')}}"><i
                    class="fal fa-wallet"></i> @lang('My wallet')</a>
        </li>
        <li>
            <a class="{{menuActive('user.advertisements.*')}}" href="{{route('user.advertisements.list')}}"><i
                    class="fal fa-ad"></i>@lang('Advertisements')</a>
        </li>
        <li>
            <a class="{{menuActive('user.buyCurrencies.*')}}"
               href="{{route('user.buyCurrencies.list','all')}}"><i
                    class="fal fa-money-check-alt"></i>@lang('Buy Currency')</a>
        </li>
        <li>
            <a class="{{menuActive('user.sellCurrencies.*')}}"
               href="{{route('user.sellCurrencies.list','all')}}"><i
                    class="fas fa-hand-holding-magic"></i>@lang('Sell Currency')</a>
        </li>
        <li>
            <a
                class="dropdown-toggle {{menuActive('user.trade.list')}}"
                data-bs-toggle="collapse"
                href="#dropdownCollapsible3"
                role="button"
                aria-expanded="false"
                aria-controls="collapseExample">
                <i class="fal fa-file-spreadsheet"></i></i></i>@lang('Trade List')
            </a>
            <div class="collapse" id="dropdownCollapsible3">
                <ul class="">
                    <li>
                        <a href="{{route('user.trade.list','running')}}"><i class="fal fa-th-large"></i>@lang('Running')
                        </a>
                    </li>
                    <li>
                        <a href="{{route('user.trade.list','complete')}}"><i
                                class="fal fa-th-large"></i>@lang('Complete')</a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <a class="{{menuActive('user.transaction')}}" href="{{route('user.transaction')}}"><i
                    class="fal fa-exchange-alt"></i>@lang('Transactions')</a>
        </li>
        <li>
            <a class="{{menuActive('user.deposit.history')}}" href="{{route('user.deposit.history')}}"><i
                    class="fal fa-wallet"></i>@lang('Deposit History')</a>
        </li>
        <li>
            <a class="{{menuActive('user.payout.history')}}" href="{{route('user.payout.history')}}"><i
                    class="fas fa-hand-holding-usd"></i>@lang('Payout History')</a>
        </li>
        <li>
            <a class="{{menuActive('user.ticket.list')}}" href="{{route('user.ticket.list')}}"><i
                    class="fas fa-headset"></i>
                </i>@lang('Support Tickets')</a>
        </li>
        <li>
            <a class="{{menuActive('user.profile')}}" href="{{route('user.profile')}}"><i
                    class="fal fa-user-edit"></i>@lang('Edit Profile')</a>
        </li>
    </ul>
    <div class="company-setting-dropdown-items">
        <button type="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{getFile(config('location.logoIcon.path').'logo.png')}}" alt="{{config('basic.site_title')}}"/>
            <span>{{config('basic.site_title')}}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-start">
            <li>
                <a href="{{route('user.list.setting')}}" class="dropdown-item">
                    <i class="fal fa-cog"></i>
                    <span>@lang('Settings')</span>
                </a>
            </li>
        </ul>
    </div>
</div>

