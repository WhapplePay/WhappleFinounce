<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.dashboard')}}" aria-expanded="false">
                        <i data-feather="home" class="feather-icon text-info"></i>
                        <span class="hide-menu">@lang('Dashboard')</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.identify-form')}}" aria-expanded="false">
                        <i data-feather="file-text" class="feather-icon text-danger"></i>
                        <span class="hide-menu">@lang('KYC / Identity Form')</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Api Settings')</span></li>

                <li class="sidebar-item {{menuActive(['admin.api-setting*'],3)}}">
                    <a class="sidebar-link" href="{{ route('admin.api-setting') }}" aria-expanded="false">
                        <i class="fas fa-cogs text-primary"></i>
                        <span class="hide-menu">@lang('Api Settings')</span>
                    </a>
                </li>


                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Currencies')</span></li>

                <li class="sidebar-item {{menuActive(['admin.*Crypto'],3)}}">
                    <a class="sidebar-link" href="{{ route('admin.listCrypto') }}" aria-expanded="false">
                        <i class="fab fa-bitcoin text-orange"></i>
                        <span class="hide-menu">@lang('Crypto')</span>
                    </a>

                </li>
                <li class="sidebar-item {{menuActive(['admin.*Fiat'],3)}}">
                    <a class="sidebar-link" href="{{ route('admin.listFiat') }}" aria-expanded="false">
                        <i class="fas fa-ruble-sign text-bel"></i>
                        <span class="hide-menu">@lang('Fiat')</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Payment Setting')</span></li>
                <li class="sidebar-item {{menuActive(['admin.payment.windows'],3)}}">
                    <a class="sidebar-link" href="{{ route('admin.payment.windows') }}" aria-expanded="false">
                        <i class="fas fa-stopwatch text-info"></i>
                        <span class="hide-menu">@lang('Payment Windows')</span>
                    </a>
                </li>
                <li class="sidebar-item {{menuActive(['admin.payment.methods','create.payment.methods','admin.edit.payment.methods'],3)}}">
                    <a class="sidebar-link" href="{{route('admin.payment.methods')}}"
                       aria-expanded="false">
                        <i class="fas fa-credit-card text-bel"></i>
                        <span class="hide-menu">@lang('Payment Methods')</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Withdraw Settings')</span></li>

                <li class="sidebar-item {{menuActive(['admin.payout.method'],3)}}">
                    <a class="sidebar-link" href="{{route('admin.payout.method')}}" aria-expanded="false">
                        <i class="fas fa-address-card text-sunflower"></i>
                        <span class="hide-menu">@lang('Withdraw Method')</span>
                    </a>
                </li>

                <li class="sidebar-item {{menuActive(['admin.payout-request'],3)}}">
                    <a class="sidebar-link" href="{{route('admin.payout-request')}}" aria-expanded="false">
                        <i class="fas fa-hand-holding-usd text-danger"></i>
                        <span class="hide-menu">@lang('Withdraw Request')</span>
                    </a>
                </li>

                <li class="sidebar-item {{menuActive(['admin.payout-log*'],3)}}">
                    <a class="sidebar-link" href="{{route('admin.payout-log')}}" aria-expanded="false">
                        <i class="fas fa-history text-warning"></i>
                        <span class="hide-menu">@lang('Withdraw Log')</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Manage Advertisements')</span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fab fa-adversal text-pome"></i>
                        <span class="hide-menu">@lang('Advertise Lists')</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="{{route('admin.advertise.list','enable')}}" class="sidebar-link">
                                <span class="hide-menu">@lang('Enabled')</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{route('admin.advertise.list','disable')}}" class="sidebar-link">
                                <span class="hide-menu">@lang('Disable') </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Manage Trades')</span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-chart-line text-tur"></i>
                        <span class="hide-menu">@lang('Trades List')</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="{{route('admin.trade.list','all')}}" class="sidebar-link">
                                <span class="hide-menu">@lang('All') </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{route('admin.trade.list','running')}}" class="sidebar-link">
                                <span class="hide-menu">@lang('Running')</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{route('admin.trade.list','reported')}}" class="sidebar-link">
                                <span class="hide-menu">@lang('Reported')<sup
                                        class="badge badge-pill badge-danger mt-2 ml-1">{{$reported}}</sup> </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{route('admin.trade.list','complete')}}" class="sidebar-link">
                                <span class="hide-menu">@lang('Complete') </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('All Transaction ')</span></li>

                <li class="sidebar-item {{menuActive(['admin.transaction*'],3)}}">
                    <a class="sidebar-link" href="{{ route('admin.transaction') }}" aria-expanded="false">
                        <i class="fas fa-exchange-alt text-danger"></i>
                        <span class="hide-menu">@lang('Transaction')</span>
                    </a>
                </li>
                <li class="sidebar-item {{menuActive(['admin.payment.log','admin.payment.search'],3)}}">
                    <a class="sidebar-link" href="{{route('admin.payment.log')}}" aria-expanded="false">
                        <i class="fas fa-history text-success"></i>
                        <span class="hide-menu">@lang('Deposit Log')</span>
                    </a>
                </li>

                {{--Manage User--}}
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Manage User')</span></li>

                <li class="sidebar-item {{menuActive(['admin.users','admin.users.search','admin.user-edit*','admin.send-email*','admin.user*'],3)}}">
                    <a class="sidebar-link" href="{{ route('admin.users') }}" aria-expanded="false">
                        <i class="fas fa-users text-success"></i>
                        <span class="hide-menu">@lang('All User')</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.kyc.users.pending') }}"
                       aria-expanded="false">
                        <i class="fas fa-spinner text-primary"></i>
                        <span class="hide-menu">@lang('Pending KYC')</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.kyc.users') }}"
                       aria-expanded="false">
                        <i class="fas fa-reply text-warning"></i>
                        <span class="hide-menu">@lang('KYC Log')</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.email-send') }}"
                       aria-expanded="false">
                        <i class="fas fa-envelope-open text-tur"></i>
                        <span class="hide-menu">@lang('Send Email')</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Support Tickets')</span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.ticket')}}" aria-expanded="false">
                        <i class="fas fa-ticket-alt text-info"></i>
                        <span class="hide-menu">@lang('All Tickets')</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.ticket',['open']) }}"
                       aria-expanded="false">
                        <i class="fas fa-spinner text-bel"></i>
                        <span class="hide-menu">@lang('Open Ticket')</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.ticket',['closed']) }}"
                       aria-expanded="false">
                        <i class="fas fa-times-circle text-danger"></i>
                        <span class="hide-menu">@lang('Closed Ticket')</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.ticket',['answered']) }}"
                       aria-expanded="false">
                        <i class="fas fa-reply text-success"></i>
                        <span class="hide-menu">@lang('Answered Ticket')</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Controls')</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.basic-controls')}}" aria-expanded="false">
                        <i class="fas fa-cogs text-primary"></i>
                        <span class="hide-menu">@lang('Basic Controls')</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-envelope text-success"></i>
                        <span class="hide-menu">@lang('Email Settings')</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="{{route('admin.email-controls')}}" class="sidebar-link">
                                <span class="hide-menu">@lang('Email Controls')</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{route('admin.email-template.show')}}" class="sidebar-link">
                                <span class="hide-menu">@lang('Email Template') </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-mobile-alt text-danger"></i>
                        <span class="hide-menu">@lang('SMS Settings')</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.sms.config') }}" class="sidebar-link">
                                <span class="hide-menu">@lang('SMS Controls')</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="{{ route('admin.sms-template') }}" class="sidebar-link">
                                <span class="hide-menu">@lang('SMS Template')</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-bullhorn text-warning"></i>
                        <span class="hide-menu">@lang('Push Notification')</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="{{route('admin.push.notify-config')}}" class="sidebar-link">
                                <span class="hide-menu">@lang('Configuration')</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="{{ route('admin.push.notify-template.show') }}" class="sidebar-link">
                                <span class="hide-menu">@lang('Template')</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-bell text-purple"></i>
                        <span class="hide-menu">@lang('In App Notification')</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="{{route('admin.notify-config')}}" class="sidebar-link">
                                <span class="hide-menu">@lang('Configuration')</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="{{ route('admin.notify-template.show') }}" class="sidebar-link">
                                <span class="hide-menu">@lang('Template')</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.plugin.config')}}" aria-expanded="false">
                        <i class="fas fa-toolbox text-danger"></i>
                        <span class="hide-menu">@lang('Plugin Configuration')</span>
                    </a>
                </li>


                <li class="sidebar-item {{menuActive(['admin.language.create','admin.language.edit*','admin.language.keywordEdit*'],3)}}">
                    <a class="sidebar-link" href="{{  route('admin.language.index') }}"
                       aria-expanded="false">
                        <i class="fas fa-language text-teal"></i>
                        <span class="hide-menu">@lang('Manage Language')</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Subscriber')</span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.subscriber.index')}}" aria-expanded="false">
                        <i class="fas fa-envelope-open text-indigo"></i>
                        <span class="hide-menu">@lang('Subscriber List')</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">@lang('Theme Settings')</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.logo-seo')}}" aria-expanded="false">
                        <i class="fas fa-image text-purple"></i><span
                            class="hide-menu">@lang('Manage Logo & SEO')</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('admin.breadcrumb')}}" aria-expanded="false">
                        <i class="fas fa-file-image text-orange"></i><span
                            class="hide-menu">@lang('Manage Breadcrumb')</span>
                    </a>
                </li>


                <li class="sidebar-item {{menuActive(['admin.template.show*'],3)}}">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-clipboard-list text-indigo"></i>
                        <span class="hide-menu">@lang('Manage Content')</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level base-level-line {{menuActive(['admin.template.show*'],1)}}">

                        @foreach(array_diff(array_keys(config('templates')),['message','template_media']) as $name)
                            <li class="sidebar-item {{ menuActive(['admin.template.show'.$name]) }}">
                                <a class="sidebar-link {{ menuActive(['admin.template.show'.$name]) }}"
                                   href="{{ route('admin.template.show',$name) }}">
                                    <span class="hide-menu">@lang(ucfirst(kebab2Title($name)))</span>
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </li>


                @php
                    $segments = request()->segments();
                    $last  = end($segments);
                @endphp
                <li class="sidebar-item {{menuActive(['admin.content.create','admin.content.show*'],3)}}">
                    <a class="sidebar-link has-arrow {{Request::routeIs('admin.content.show',$last) ? 'active' : '' }}"
                       href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-clipboard-list text-teal"></i>
                        <span class="hide-menu">@lang('Content Settings')</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level base-level-line {{menuActive(['admin.content.create','admin.content.show*'],1)}}">
                        @foreach(array_diff(array_keys(config('contents')),['message','content_media']) as $name)
                            <li class="sidebar-item {{($last == $name) ? 'active' : '' }} ">
                                <a class="sidebar-link {{($last == $name) ? 'active' : '' }}"
                                   href="{{ route('admin.content.index',$name) }}">
                                    <span class="hide-menu">@lang(ucfirst(kebab2Title($name)))</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                <li class="list-divider"></li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
