@extends($theme.'layouts.user')
@section('title')
    @lang('New Trade Request')
@endsection
@section('content')
    <script>
        "use strict"

        function getCountDown(elementId, seconds) {
            var times = seconds;
            var x = setInterval(function () {
                var distance = times * 1000;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById(elementId).innerHTML = days + "d: " + hours + "h " + minutes + "m " + seconds + "s ";
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById(elementId).innerHTML = "COMPLETE";
                }
                times--;
            }, 1000);
        }
    </script>
    <div class="container-fluid" id="pushChatArea" v-cloak>
        <div class="main row">
            <section class="conversation-section">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="header-text text-center mb-2">
                            @if($trade->owner_id == auth()->user()->id)
                                <h4>
                                    {{$trade->type == 'sell'?'Selling':'Buying'}} {{getAmount($trade->receive_amount,8)}} {{optional($trade->receiverCurrency)->code}}
                                    @lang('FOR') {{getAmount($trade->pay_amount+0)}} {{optional($trade->currency)->code}} @lang('via')
                                    <div class="flex-wrap mt-1">
                                        @foreach($trade->gateways as $gateway)
                                            <span class="gateway-color"
                                                  style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                        @endforeach
                                    </div>
                                </h4>
                            @else
                                <h4>
                                    {{$trade->type == 'sell'?'Buying':'Selling'}} {{getAmount($trade->receive_amount,8)}} {{optional($trade->receiverCurrency)->code}} @lang('FOR')
                                    {{getAmount($trade->pay_amount+0)}} {{optional($trade->currency)->code}} @lang('via')
                                    <div class="flex-wrap mt-1">
                                        @foreach($trade->gateways as $gateway)
                                            <span class="gateway-color"
                                                  style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                        @endforeach
                                    </div>
                                </h4>
                            @endif
                            <h5>@lang('Exchange Rate:') {{getAmount($trade->rate+0,2)}} {{optional($trade->currency)->code}}
                                /{{optional($trade->receiverCurrency)->code}}</h5>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="inbox-wrapper">
                            <!-- top bar -->
                            <div class="top-bar">
                                <div>
                                    @if($trade->owner_id == auth()->user()->id)
                                        <img class="user img-fluid"
                                             src="{{getFile(config('location.user.path').optional($trade->sender)->image)}}"
                                             alt="..."/>
                                        <i class="online position-absolute fa fa-circle text-{{(optional($trade->sender)->lastSeen == true) ?trans('success'):trans('warning') }}"
                                           title="{{(optional($trade->sender)->lastSeen == true) ?trans('Online'):trans('Away') }}"></i>
                                    @else
                                        <img class="user img-fluid"
                                             src="{{getFile(config('location.user.path').optional($trade->owner)->image)}}"
                                             alt="..."/>
                                        <i class="online position-absolute fa fa-circle text-{{(optional($trade->owner)->lastSeen == true) ?trans('success'):trans('warning') }}"
                                           title="{{(optional($trade->owner)->lastSeen == true) ?trans('Online'):trans('Away') }}"></i>
                                    @endif
                                    @if($trade->owner_id == auth()->user()->id)
                                        <a href="{{route('user.profile.page',optional($trade->sender)->id)}}"><span
                                                class="name">@lang(optional($trade->sender)->username)</span></a>
                                    @else
                                        <a href="{{route('user.profile.page',optional($trade->owner)->id)}}"><span
                                                class="name">@lang(optional($trade->owner)->username)</span></a>
                                    @endif
                                </div>
                                <div>
                                    <button class="close-btn" id="infoBtn" @click.prevent="sync">
                                        <i class="fal fa-sync"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- chats -->
                            <div class="chats" ref="chatArea">
                                <div v-for="(item, index) in items">
                                    <div v-if="item.chatable_id == auth_id && item.chatable_type == auth_model"
                                         class="chat-box this-side"
                                         :title="item.chatable.username">
                                        <div class="text-wrapper">
                                            <div class="text">
                                                <p>@{{item.description}}</p>
                                            </div>
                                            <span class="time">@{{item.formatted_date}}</span>
                                        </div>
                                        <div class="img">
                                            <img class="img-fluid" :src="item.chatable.imgPath" alt="..."/>
                                        </div>
                                    </div>
                                    <div v-else class="chat-box opposite-side"
                                         :title="item.chatable.username">
                                        <div class="img">
                                            <img v-if="item.chatable.imgPath" class="img-fluid"
                                                 :src="item.chatable.imgPath" alt="..."/>
                                            <img v-else class="img-fluid" :src="logo" alt="..."/>
                                        </div>
                                        <div class="text-wrapper">
                                            <div class="text">
                                                <p v-html="item.description"></p>
                                            </div>
                                            <span class="time">@{{item.formatted_date}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- typing area -->
                            @if($trade->status != 3 && $trade->status != 4 && $trade->status != 8)
                                <form @submit.prevent="send" enctype="multipart/form-data" method="post">
                                    <div class="typing-area">
                                        <div class="input-group">
                                            <input type="text" class="form-control" v-model.trim="message"/>
                                            <button class="submit-btn" @click.prevent="send">
                                                <i class="fal fa-paper-plane" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="dispute-trade">
                            <div class="d-flex justify-content-between mb-4">
                                <span>#{{$trade->trade_number}}</span>
                                @if($trade->status == 3)
                                    <span class="current-status cancel">@lang('Cancelled')</span>
                                @elseif($trade->status == 1)
                                    <span class="current-status paid">@lang('Buyer Paid')</span>
                                @elseif($trade->status == 4)
                                    <span class="current-status complete">@lang('Completed')</span>
                                @elseif($trade->status == 5)
                                    <span class="current-status reported">@lang('Reported')</span>
                                @elseif($trade->status == 0)
                                    <span class="current-status warning">@lang('Pending')</span>
                                @elseif($trade->status == 6 || $trade->status == 7)
                                    <span class="badge badge-info">@lang('Escrow Funded')</span>
                                @elseif($trade->status == 8)
                                    <span class="current-status complete">@lang('Resolved')</span>
                                @endif
                            </div>
                            @if($trade->status != 3 && $trade->status != 4 && $trade->status != 8)
                                @if($trade->owner_id != auth()->user()->id)
                                    @if($trade->status == 0 && $trade->type == 'sell')
                                        <p class="text-danger text-center">@lang('Please pay') {{getAmount($trade->pay_amount,2)}} {{optional($trade->currency)->code}} @lang('using')@foreach($trade->gateways as $gateway)
                                                <span class="gateway-color"
                                                      style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                            @endforeach</p>
                                        <p> {{getAmount($trade->receive_amount,8)}} {{optional($trade->receiverCurrency)->code}} @lang('will be added to your wallet after
                                            confirmation about the
                                            payment.')</p>
                                    @endif
                                    @if($trade->status == 0 && $trade->type == 'buy')
                                        <p>@lang('Once the buyer has confirmed your payment then') <span
                                                class="theme-color">{{getAmount($trade->receive_amount,8)}} {{optional($trade->receiverCurrency)->code}}</span> @lang('will be available for release.')
                                        </p>
                                    @endif
                                    @if($trade->status == 5)
                                        <p class="bg-primary-dark text-danger">@lang('This trade is Reported by')
                                            <span class="text-warning">{{optional($trade->disputeBy)->username}}</span>
                                            @lang('. Please wait for the system response.')</p>
                                    @endif
                                    @if($trade->status == 1 && \Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) > \Carbon\Carbon::now() && $trade->type == 'sell')
                                        <p class="bg-primary-dark">@lang('You can dispute this trade after')</p>
                                    @endif
                                    @if($trade->time_remaining && \Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) > \Carbon\Carbon::now())
                                        <p id="counter" class="theme-color"></p>
                                        <script>getCountDown("counter", {{\Carbon\Carbon::parse($trade->time_remaining)->diffInSeconds()}});</script>
                                    @endif
                                    @if($trade->type == 'sell')
                                    <div class="d-flex mb-4">
                                        <button class="btn-custom w-50 mx-1 dispute-btn" data-bs-target="#cancelModal" data-bs-toggle="modal">
                                            <i class="fal fa-times-circle"></i> @lang('Cancel')
                                        </button>
                                        @if ($trade->status == 1) 
                                            {{-- <button class="btn-custom w-50 mx-1 release-btn" disabled>
                                                <i class="fal fa-check-circle"></i> @lang('I have paid')
                                            </button> --}}
                                        @elseif (!in_array($trade->status, [0,1,5]) &&$hasRunningTrades)
                                            <p>Another trade is ongoing. Please be patient</p>
                                        @elseif (!$hasRunningTrades )
                                            <button class="btn-custom w-50 mx-1 release-btn" data-bs-target="#paidModal" data-bs-toggle="modal">
                                                <i class="fal fa-check-circle"></i> @lang('I have paid')
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <p>You cannot place a new trade for the same advertisement while there is another running trade.</p>
                                @endif
                                
                                @if($trade->status == 1 && $trade->type == 'buy')
                                        <div class="d-flex mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#disputeModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> @lang('Dispute')
                                            </button>
                                            <button class="btn-custom w-50 mx-1 release-btn"
                                                    data-bs-target="#releaseModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-check-circle text-white"></i> @lang('Payment Received')
                                            </button>
                                        </div>
                                    @endif
                                    @if(\Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) < \Carbon\Carbon::now() && $trade->status ==1 && $trade->type == 'sell')
                                        <div class="d-flex justify-content-center mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#disputeModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> @lang('Dispute')
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    @if($trade->status == 1 && \Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) > \Carbon\Carbon::now() &&$trade->type == 'buy')
                                        <p class="bg-primary-dark">@lang('You can dispute this trade after')</p>
                                    @endif
                                    @if($trade->time_remaining && \Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) > \Carbon\Carbon::now())
                                        <p id="counter" class="theme-color"></p>
                                        <script>getCountDown("counter", {{\Carbon\Carbon::parse($trade->time_remaining)->diffInSeconds()}});</script>
                                    @endif
                                    @if($trade->status == 0 && $trade->type == 'buy')
                                        <p class="text-danger text-center">@lang('Please pay') {{getAmount($trade->pay_amount,2)}} {{optional($trade->currency)->code}} @lang('using')
                                        <div class="d-flex flex-wrap">
                                            @foreach($trade->gateways as $gateway)
                                                <span class="gateway-color"
                                                      style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                            @endforeach
                                        </div>
                                        </p>
                                        <p> {{getAmount($trade->receive_amount,8)}} {{optional($trade->receiverCurrency)->code}} @lang('will be added to your wallet after
                                            confirmation about the
                                            payment.')</p>
                                        <div class="d-flex mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#cancelModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> @lang('Cancel')
                                            </button>
                                            <button class="btn-custom w-50 mx-1 release-btn"
                                                    data-bs-target="#paidModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-check-circle"></i> @lang('I have paid')
                                            </button>
                                        </div>
                                    @endif
                                    @if(\Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) < \Carbon\Carbon::now() && $trade->status ==1 && $trade->type == 'buy')
                                        <div class="d-flex justify-content-center mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#disputeModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> @lang('Disputes')
                                            </button>
                                        </div>
                                    @endif
                                    @if($trade->status == 5)
                                        <p class="bg-primary-dark text-danger">@lang('This trade is Reported by')
                                            <span class="text-warning">{{optional($trade->disputeBy)->username}}</span>
                                            @lang('Please wait for the system response.')</p>
                                    @else
                                        @if($trade->type != 'buy' && $trade->status != 0 )
                                            <p class="bg-primary-dark">@lang('The buyer can dispute anytime this trade after countdown time.')</p>
                                        @endif
                                    @endif
                                    @if($trade->status == 1 && $trade->type == 'sell')
                                        <div class="d-flex mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#disputeModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> @lang('Dispute')
                                            </button>
                                            <button class="btn-custom w-50 mx-1 release-btn"
                                                    data-bs-target="#releaseModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-check-circle"></i> @lang('release')
                                            </button>
                                        </div>
                                    @endif
                                    @if($trade->status == 0 && $trade->type == 'sell')
                                        <div class="d-flex justify-content-center mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#cancelModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> @lang('Cancel')
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            @endif
                            <div class="accordion">
                                <div class="accordion-item">
                                    <h5 class="accordion-header" id="headingOne">
                                        <button
                                            class="accordion-button collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne"
                                            aria-expanded="false"
                                            aria-controls="collapseOne">
                                            @lang('trade information')
                                        </button>
                                    </h5>
                                    <div
                                        id="collapseOne"
                                        class="accordion-collapse collapse"
                                        aria-labelledby="headingOne"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="rate">
                                                <ul>
                                                    @if($trade->type == 'buy')
                                                        <li>@lang('Buyer Name:') <span
                                                                class="caption">{{optional($trade->owner)->username}}</span>
                                                        </li>
                                                        <li>@lang('Seller Name:') <span
                                                                class="caption">{{optional($trade->sender)->username}}</span>
                                                        </li>
                                                    @else
                                                        <li>@lang('Buyer Name:') <span
                                                                class="caption">{{optional($trade->sender)->username}}</span>
                                                        </li>
                                                        <li>@lang('Seller Name:') <span
                                                                class="caption">{{optional($trade->owner)->username}}</span>
                                                        </li>
                                                    @endif

                                                    <li><span class="">@lang('Rate:')</span><span
                                                            class="caption">{{getAmount($trade->rate,2)}} {{optional($trade->currency)->code}}
                                                        /{{optional($trade->receiverCurrency)->code}}</span>
                                                    </li>
                                                    <li>{{optional($trade->currency)->name}}: <span
                                                            class="caption">{{getAmount($trade->pay_amount,2)}} {{optional($trade->currency)->code}}</span>
                                                    </li>
                                                    <li>{{optional($trade->receiverCurrency)->name}} <span
                                                            class="caption">{{getAmount($trade->receive_amount,8)}} {{optional($trade->receiverCurrency)->code}}</span>
                                                    </li>

                                                    <li>@lang('Payment Method:') <span
                                                            class="caption">{{$trade->payment_window}} @lang('Minutes')</span>
                                                    </li>
                                                    @if($trade->status == 4)
                                                        <li>@lang('Admin Charge:') <span
                                                                class="caption text-danger">{{$trade->admin_charge}} {{optional($trade->receiverCurrency)->code}}</span>
                                                        </li>
                                                        <li>@lang('Processing Time:') <span
                                                                class="caption">{{$trade->processing_minutes}} @lang('Minutes')</span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="mb-3">@lang('Instructions to be followed')</h6>
                                <div class="accordion-item">
                                    <h5 class="accordion-header" id="headingTwo">
                                        <button
                                            class="accordion-button collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo"
                                            aria-expanded="false"
                                            aria-controls="collapseTwo">
                                            @lang('terms of trade')
                                        </button>
                                    </h5>
                                    <div
                                        id="collapseTwo"
                                        class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            {{optional($trade->advertise)->terms_of_trade}}
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h5 class="accordion-header" id="headingThree">
                                        <button
                                            class="accordion-button collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree"
                                            aria-expanded="false"
                                            aria-controls="collapseThree">
                                            @lang('payment details')
                                        </button>
                                    </h5>
                                    <div
                                        id="collapseThree"
                                        class="accordion-collapse collapse"
                                        aria-labelledby="headingThree"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            {{optional($trade->advertise)->payment_details}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('loadModal')
    <!-- Dispute Modal -->
    <div class="modal fade" id="disputeModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">@lang('Dispute Confirmation')</h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="{{route('user.trade.dispute')}}" method="post">
                    @csrf
                    <input type="hidden" name="tradeId" value="{{$trade->id}}">
                    <div class="modal-body">
                        <div class="row g-4">
                            <p>@lang('Are you sure that you have release this trade?')</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn-custom">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Release Modal -->
    <div class="modal fade" id="releaseModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">@lang('Release Confirmation')</h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="{{route('user.trade.release')}}" method="post">
                    @csrf
                    <input type="hidden" name="tradeId" value="{{$trade->id}}">
                    <div class="modal-body">
                        <div class="row g-4">
                            <p>@lang('Are you sure that you have release this trade?')</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn-custom">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">@lang('Cancel Confirmation')</h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="{{route('user.trade.cancel')}}" method="post">
                    @csrf
                    <input type="hidden" name="tradeId" value="{{$trade->id}}">
                    <div class="modal-body">
                        <div class="row g-4">
                            <p>@lang('Are you sure to cancel this trade?')</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn-custom">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Paid Modal -->
    <div class="modal fade" id="paidModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">@lang('Paid Confirmation')</h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="{{route('user.trade.paid')}}" method="post">
                    @csrf
                    <input type="hidden" name="tradeId" value="{{$trade->id}}">
                    <div class="modal-body">
                        <div class="row g-4">
                            <p>@lang('Are you sure that you have paid the amount?')</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn-custom">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
@push('style')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@push('script')
    <script>
          const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        'use strict';
        let pushChatArea = new Vue({
            el: "#pushChatArea",
            data: {
                items: [],
                auth_id: "{{auth()->id()}}",
                auth_model: "App\\Models\\User",
                logo: "{{asset(config('location.logoIcon.path').'logo.png')}}",
                message: ''
            },
            beforeMount() {
                this.getNotifications();
                this.pushNewItem();
            },
            methods: {
                getNotifications() {
                    let app = this;
                    app.scrollToBottom();
                    axios.get("{{ route('user.push.chat.show',$trade->hash_slug) }}")
                        .then(function (res) {
                            app.items = res.data;
                        })
                },

                pushNewItem() {
                    let app = this;
                    // Pusher.logToConsole = true;
                    let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                        encrypted: true,
                        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                    });

                    let channel = pusher.subscribe('offer-chat-notification.' + "{{ $trade->hash_slug }}");
                    console.log(channel)

                    channel.bind('App\\Events\\OfferChatNotification', function (data) {
                        app.items.push(data.message);
                        var x = document.getElementById("myAudio");
                        x.play();
                        Vue.nextTick(() => {
                            let messageDisplay = app.$refs.chatArea
                            messageDisplay.scrollTop = messageDisplay.scrollHeight
                        })

                    });
                    channel.bind('App\\Events\\UpdateOfferChatNotification', function (data) {
                        app.getNotifications();
                    });
                },

                send() {
                    let app = this;
                    if (app.message.length == 0) {
                        Notiflix.Notify.Failure(`{{trans('Type your message')}}`);
                        return 0;
                    }

                    axios.post("{{ route('user.push.chat.newMessage')}}", {
                        trade_id: "{{$trade->id}}",
                        message: app.message
                    }).then(function (res) {

                        if (res.data.errors) {
                            var err = res.data.errors;
                            for (const property in err) {
                                Notiflix.Notify.Failure(`${err[property]}`);
                            }
                            return 0;
                        }

                        app.message = '';

                        if (res.data.success == true) {
                            Vue.nextTick(() => {
                                let messageDisplay = app.$refs.chatArea
                                messageDisplay.scrollTop = messageDisplay.scrollHeight
                            })
                        }
                    }).catch(function (error) {

                    });

                },
                sync() {
                    location.reload();
                },
                scrollToBottom() {
                    setTimeout(() => {
                        let messagesContainer = this.$el.querySelector(".chats");
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }, 50);
                },
            }
        });
    </script>
    @if($errors->any())
        <script>
            'use strict';
            @foreach ($errors->all() as $error)
            Notiflix.Notify.Failure(`{{trans($error)}}`);
            @endforeach
        </script>
    @endif
@endpush
