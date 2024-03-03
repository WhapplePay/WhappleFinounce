@extends($theme.'layouts.user')
@section('title',trans('Dashboard'))
@section('content')
    <!-- main -->
    <div class="container-fluid">
        <div class="main row" id="firebase-app">
            <div v-if="user_foreground == '1' || user_background == '1'">
                <div class="col-12" v-if="notificationPermission == 'default' && !is_notification_skipped" v-cloak>
                    <div class="alert d-flex justify-content-between align-items-start" role="alert">
                        <div>
                            <i class="fal fa-info-circle"></i> @lang('Do not miss any single important notification! Allow your
                        browser to get instant push notification')

                            <button class="btn-custom mx-3" id="allow-notification">@lang('Allow me')</button>
                        </div>
                        <button class="close-btn pt-1" @click.prevent="skipNotification"><i class="fal fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12" v-if="notificationPermission == 'denied' && !is_notification_skipped" v-cloak>
                    <div class="alert d-flex justify-content-between align-items-start" role="alert">
                        <div>
                            <i class="fal fa-info-circle"></i> @lang('Please allow your browser to get instant push notification.
                        Allow it from
                        notification setting.')
                        </div>
                        <button class="close-btn pt-1" @click.prevent="skipNotification"><i class="fal fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-box">
                            <h5>@lang('Advertisement')</h5>
                            <h3>{{$advertise['totalAdvertise']}}</h3>
                            <i class="fal fa-ad"></i>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-box box-4">
                            <h5>@lang('Total Trades')</h5>
                            <h3>{{$trades['totalTrade']}}</h3>
                            <i class="fal fa-file-spreadsheet"></i>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-box box-2">
                            <h5>@lang('Running Trades')</h5>
                            <h3>{{$trades['runningTrade']}}</h3>
                            <i class="fal fa-spinner"></i>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-box box-3">
                            <h5>@lang('Complete Trades')</h5>
                            <h3>{{$trades['completeTrade']}}</h3>
                            <i class="fal fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <!-- table -->
                <div class="table-parent table-responsive">
                    <h6 class="my-3 text-white">@lang('Last 10 Trade Lists')</h6>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>@lang('Trade Number')</th>
                            <th>@lang('With')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Currency')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Rate')</th>
                            <th>@lang('Crypto Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Requested On')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentTrades as $item)
                            <tr>
                                <td data-label="@lang('SL No.')">{{$item->trade_number}}</td>
                                <td data-label="@lang('With')">
                                    <a href="javascript:void(0)">
                                        <div class="d-lg-flex d-block align-items-center">
                                            @if($item->owner_id != auth()->user()->id)
                                                <div class="me-3"><img
                                                        src="{{getFile(config('location.user.path').optional($item->owner)->image) }}"
                                                        alt="user" class="rounded-circle" width="45"
                                                        height="45">
                                                    <i class="tb-online position-absolute fa fa-circle text-{{(optional($item->owner)->lastSeen == true) ?trans('success'):trans('danger') }}"
                                                       title="{{(optional($item->owner)->lastSeen == true) ?trans('Online'):trans('Away') }}"></i>
                                                </div>
                                                <div class="">
                                                    <h6 class="text-white mb-0 text-lowercase">@lang(optional($item->owner)->username)</h6>
                                                    <span
                                                        class="text-muted font-10">{{optional($item->owner)->total_trade}} @lang('Trades') |</span>
                                                    @if(optional($item->owner)->total_trade>0)
                                                        <span
                                                            class="text-muted font-10">@lang('Completion') {{number_format(optional($item->owner)->completed_trade*100/optional($item->owner)->total_trade,2)}}
                                                            %</span>
                                                    @else
                                                        <span
                                                            class="text-muted font-10">@lang('Completion') 0
                                                            %</span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="me-3"><img
                                                        src="{{getFile(config('location.user.path').optional($item->sender)->image) }}"
                                                        alt="user" class="rounded-circle" width="45"
                                                        height="45">
                                                    <i class="tb-online position-absolute fa fa-circle text-{{(optional($item->sender)->lastSeen == true) ?trans('success'):trans('danger') }}"
                                                       title="{{(optional($item->sender)->lastSeen == true) ?trans('Online'):trans('Away') }}"></i>
                                                </div>
                                                <div class="">
                                                    <h6 class="text-white mb-0 text-lowercase">@lang(optional($item->sender)->username)</h6>
                                                    <span
                                                        class="text-muted font-10">{{optional($item->sender)->total_trade}} @lang('Trades') |</span>
                                                    @if(optional($item->sender)->total_trade>0)
                                                        <span
                                                            class="text-muted font-10">@lang('Completion') {{number_format(optional($item->sender)->completed_trade*100/optional($item->sender)->total_trade,2)}}
                                                            %</span>
                                                    @else
                                                        <span
                                                            class="text-muted font-10">@lang('Completion') 0
                                                            %</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </td>
                                @if($item->owner_id == auth()->user()->id)
                                    <td data-label="@lang('Type')"><span
                                            class="badge {{($item->type == 'buy')? 'bg-success' : 'bg-warning'}}">@lang($item->type)</span>
                                    </td>
                                @else
                                    <td data-label="@lang('Type')"><span
                                            class="badge {{($item->type == 'buy')? 'bg-warning' : 'bg-success'}}">{{$item->type == 'buy'? 'sell':'buy'}}</span>
                                    </td>
                                @endif
                                <td data-label="@lang('Currency')">@lang(optional($item->currency)->code)</td>
                                <td data-label="@lang('Payment Method')">@foreach($item->gateways as $gateway)
                                        <span class="gateway-color"
                                              style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                    @endforeach</td>
                                <td data-label="@lang('Rate')">{{getAmount($item->rate+0,2)}} {{optional($item->currency)->code}}
                                    /{{optional($item->receiverCurrency)->code}}</td>
                                <td data-label="@lang('Crypto Amount')">{{getAmount($item->receive_amount+0,8)}} {{optional($item->receiverCurrency)->code}}</td>
                                <td data-label="@lang('Status')">
                                    @if($item->status == 0)
                                        <span class="badge bg-warning">@lang('Pending')</span>
                                    @elseif($item->status == 1)
                                        <span class="badge bg-success">@lang('Buyer Paid')</span>
                                    @elseif($item->status == 3)
                                        <span class="badge bg-danger">@lang('Canceled')</span>
                                    @elseif($item->status == 4)
                                        <span class="badge bg-success">@lang('Completed')</span>
                                    @elseif($item->status == 5)
                                        <span class="badge bg-danger">@lang('Reported')</span>
                                    @elseif($item->status == 6 || $item->status == 7)
                                        <span class="badge bg-primary">@lang('Escrow Refunded')</span>
                                    @elseif($item->status == 8)
                                        <span class="badge bg-primary">@lang('Resolved')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Requested On')">{{diffForHumans($item->created_at)}}</td>
                                <td data-label="@lang('Action')" class="action">
                                    <a href="{{route('user.buyCurrencies.tradeDetails',$item->hash_slug)}}">
                                        <button class="btn-custom">
                                        @lang('Details')</a>
                                    </button>
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
            </div>
        </div>
    </div>
@endsection
@if($firebaseNotify)
    @push('script')
        <script type="module">

            import {initializeApp} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
            import {
                getMessaging,
                getToken,
                onMessage
            } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-messaging.js";

            const firebaseConfig = {
                apiKey: "{{$firebaseNotify->api_key}}",
                authDomain: "{{$firebaseNotify->auth_domain}}",
                projectId: "{{$firebaseNotify->project_id}}",
                storageBucket: "{{$firebaseNotify->storage_bucket}}",
                messagingSenderId: "{{$firebaseNotify->messaging_sender_id}}",
                appId: "{{$firebaseNotify->app_id}}",
                measurementId: "{{$firebaseNotify->measurement_id}}"
            };

            const app = initializeApp(firebaseConfig);
            const messaging = getMessaging(app);
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('{{ getProjectDirectory() }}' + `/firebase-messaging-sw.js`, {scope: './'}).then(function (registration) {
                        requestPermissionAndGenerateToken(registration);
                    }
                ).catch(function (error) {
                });
            } else {
            }

            onMessage(messaging, (payload) => {
                if (payload.data.foreground || parseInt(payload.data.foreground) == 1) {
                    const title = payload.notification.title;
                    const options = {
                        body: payload.notification.body,
                        icon: payload.notification.icon,
                    };
                    new Notification(title, options);
                }
            });

            function requestPermissionAndGenerateToken(registration) {
                document.addEventListener("click", function (event) {
                    if (event.target.id == 'allow-notification') {
                        Notification.requestPermission().then((permission) => {
                            if (permission === 'granted') {
                                getToken(messaging, {
                                    serviceWorkerRegistration: registration,
                                    vapidKey: "{{$firebaseNotify->vapid_key}}"
                                })
                                    .then((token) => {
                                        $.ajax({
                                            url: "{{ route('user.save.token') }}",
                                            method: "post",
                                            data: {
                                                token: token,
                                            },
                                            success: function (res) {
                                            }
                                        });
                                        window.newApp.notificationPermission = 'granted';
                                    });
                            } else {
                                window.newApp.notificationPermission = 'denied';
                            }
                        });
                    }
                });
            }
        </script>
        <script>
            window.newApp = new Vue({
                el: "#firebase-app",
                data: {
                    user_foreground: '',
                    user_background: '',
                    notificationPermission: Notification.permission,
                    is_notification_skipped: sessionStorage.getItem('is_notification_skipped') == '1'
                },
                mounted() {
                    this.user_foreground = "{{$firebaseNotify->user_foreground}}";
                    this.user_background = "{{$firebaseNotify->user_background}}";
                },
                methods: {
                    skipNotification() {
                        sessionStorage.setItem('is_notification_skipped', '1');
                        this.is_notification_skipped = true;
                    }
                }
            });
        </script>
    @endpush
@endif
