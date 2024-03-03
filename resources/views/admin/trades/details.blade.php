@extends('admin.layouts.app')
@section('title')
    @lang('Trade Details')
@endsection
@section('content')
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0">
        <div class="card-body">

            <div class="row no-gutters">
                <div class="col-lg-4 col-xl-4 border-right pr-2">
                    <div class="p-3 mb-4 shadow">
                        <div class="row d-flex justify-content-between px-2 mb-4">
                            <h5><i class="fa fa-info-circle"></i> @lang('Trade Information')</h5>
                            @if($trade->status == 3)
                                <span class="badge badge-danger">@lang('Cancelled')</span>
                            @elseif($trade->status == 0)
                                <span class="badge badge-warning">@lang('Pending')</span>
                            @elseif($trade->status == 1)
                                <span class="badge badge-info">@lang('Buyer Paid')</span>
                            @elseif($trade->status == 4)
                                <span class="badge badge-success">@lang('Completed')</span>
                            @elseif($trade->status == 5)
                                <span class="badge badge-danger">@lang('Reported')</span>
                            @elseif($trade->status == 6 || $trade->status == 7)
                                <span class="badge badge-info">@lang('Escrow Funded')</span>
                                   @elseif($trade->status == 8)
                                <span class="badge badge-primary">@lang('Resolved')</span>
                            @endif
                        </div>
                        <hr>
                        <ul class="rate">
                            <li class="mb-3">@lang('Trade Number:') <span
                                    class="caption text-dark">#{{$trade->trade_number}}</span>
                            </li>
                            @if($trade->status == 3)
                                <li class="mb-3">@lang('Canceled By') <span
                                        class="caption text-danger">{{$trade->cancelBy->username}}</span>
                                </li>
                                <li class="mb-3">@lang('Canceled At') <span
                                        class="caption text-danger">{{dateTime($trade->cancel_at)}}</span>
                                </li>
                            @endif
                            @if($trade->status == 5)
                                <li class="mb-3">@lang('Reported By') <span
                                        class="caption text-danger">{{$trade->disputeBy->username}}</span>
                                </li>
                                <li class="mb-3">@lang('Reported At') <span
                                        class="caption text-danger">{{dateTime($trade->dispute_at)}}</span>
                                </li>
                            @endif
                            @if($trade->type == 'buy')
                                <li class="mb-3">@lang('Buyer Name:') <span
                                        class="caption"><a
                                            href="{{route('admin.user-edit',$trade->owner_id)}}">{{optional($trade->owner)->username}}</a></span>
                                </li>
                                <li class="mb-3">@lang('Seller Name:') <span
                                        class="caption"><a
                                            href="{{route('admin.user-edit',$trade->sender_id)}}">{{optional($trade->sender)->username}}</a></span>
                                </li>
                            @else
                                <li class="mb-3">@lang('Buyer Name:') <span
                                        class="caption"><a
                                            href="{{route('admin.user-edit',$trade->sender_id)}}">{{optional($trade->sender)->username}}</a></span>
                                </li>
                                <li class="mb-3">@lang('Seller Name:') <span
                                        class="caption"><a
                                            href="{{route('admin.user-edit',$trade->owner_id)}}">{{optional($trade->owner)->username}}</a></span>
                                </li>
                            @endif
                            <li class="mb-3"><span class="">@lang('Payment Method:')</span><span
                                    class="caption">@foreach($trade->gateways as $gateway)
                                        <span class="gateway-color"
                                              style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                    @endforeach</span>
                            <li class="mb-3"><span class="">@lang('Rate:')</span><span
                                    class="caption">{{getAmount($trade->rate+0,2)}} {{optional($trade->currency)->code}}
                                                        /{{optional($trade->receiverCurrency)->code}}</span>
                            </li>
                            <li class="mb-3">{{optional($trade->currency)->name}}: <span
                                    class="caption">{{getAmount($trade->pay_amount,2)}} {{optional($trade->currency)->code}}</span>
                            </li>
                            <li class="mb-3">{{optional($trade->receiverCurrency)->name}} <span
                                    class="caption">{{getAmount($trade->receive_amount,8)}} {{optional($trade->receiverCurrency)->code}}</span>
                            </li>
                            <li class="mb-3">@lang('Payment Window:') <span
                                    class="caption">{{$trade->payment_window}} @lang('Minutes')</span>
                            </li>
                            @if($trade->status == 4)
                                <li class="mb-3">@lang('Admin Charge:') <span
                                        class="caption">{{$trade->admin_charge}} {{optional($trade->receiverCurrency)->code}}</span>
                                </li>
                                <li class="mb-3">@lang('Processing Time:') <span
                                        class="caption">{{$trade->processing_minutes}} @lang('Minutes')</span>
                                </li>
                            @endif
                        </ul>
                        @if($trade->status == 5)
                            <hr>
                            <div class="row d-flex justify-content-between px-2 mb-4">
                                <button class="btn btn-primary w-40" data-target="#sellerReturn" data-toggle="modal"><i
                                        class="fa fa-undo"></i> @lang('In Favor of Seller')
                                </button>
                                <button class="btn btn-success w-40" data-target="#buyerReturn" data-toggle="modal"><i
                                        class="fa fa-check-circle"></i> @lang('In Favor of Buyer')</button>
                            </div>
                        @endif
                        <hr>
                        <div class="row d-flex justify-content-center px-2">
                            <a href="javascript:void(0)" data-target="#termsCondition" data-toggle="modal"><p
                                    class="mr-4"><i class="fa fa-info-circle"></i> @lang('Terms of Trade')</p></a>
                            <a href="javascript:void(0)" data-target="#paymentDetails" data-toggle="modal"><p><i
                                        class="fa fa-info-circle"></i> @lang('Payments Details')</p></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8  col-xl-8">
                    @if(!empty($persons))
                        <div class="p-3 mb-4 shadow">
                            <div class="report  justify-content-center " id="pushChatArea">
                                <audio id="myAudio">
                                    <source src="{{asset('assets/admin/css/sound.mp3')}}" type="audio/mpeg">
                                </audio>
                                <div class="card ">
                                    <div
                                        class="adiv   justify-content-between align-items-center text-white p-2 d-flex">
                                        <p><i class="fas fa-users "></i> {{trans('Conversation')}}</p>
                                        <div class="d-flex user-chatlist">
                                            @if(!empty($persons))
                                                @forelse($persons as $person)
                                                    <div class="d-flex no-block align-items-center">
                                                        <a href="javascript:void(0)"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="{{$person->username}}"
                                                           class="mr-1 position-relative">

                                                            <i class="batti position-absolute fa fa-circle text-{{($person->lastSeen == true) ?'success':'warning' }} font-12"
                                                               title="{{($person->lastSeen == true) ?'Online':'Away' }}"></i>
                                                            <img src="{{$person->imgPath}}"
                                                                 alt="user" class="rounded-circle " width="30"
                                                                 height="30">
                                                        </a>
                                                    </div>
                                                @empty
                                                @endforelse
                                            @endif
                                        </div>
                                    </div>
                                    <div class="chat-length" ref="chatArea">
                                        <div v-for="(item, index) in items">
                                            <div
                                                v-if=" item.chatable_type == auth_model"
                                                class="d-flex flex-row justify-content-end p-3 "
                                                :title="item.chatable.username">
                                                <div
                                                    class="bg-white mr-2 pt-1 pb-4  pl-2 pr-2 position-relative mw-130">
                                                    <span class="text-wa">@{{item.description}}</span>
                                                    <span class="timmer">@{{item.formatted_date}}</span>

                                                </div>
                                                <img
                                                    :src="item.chatable.imgPath"
                                                    width="30" height="30">
                                            </div>

                                            <div v-else class="d-flex flex-row justify-content-start p-3"
                                                 :title="item.chatable.username">
                                                <img :src="item.chatable.imgPath" width="30" height="30">
                                                <div class="chat ml-2 pt-1 pb-4  pl-2 pr-5 position-relative mw-130">
                                                    @{{item.description}}
                                                    <span class="timmer">@{{item.formatted_date}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($trade->status != 3 && $trade->status != 4 && $trade->status != 8)
                                        <form @submit.prevent="send" enctype="multipart/form-data" method="post">
                                            <div class="writing-box d-flex justify-content-between align-items-center">
                                                <div class="input--group form-group px-3 ">
                                                    <input class="form--control type_msg" v-model.trim="message"
                                                           placeholder="{{trans('Type your message')}}"/>
                                                </div>
                                                <div class="send text-center">
                                                    <button type="button" class="btn btn-success btn--success "
                                                            @click="send">
                                                        <i class="fas fa-paper-plane "></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Return Seller Modal -->
    <div id="sellerReturn" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">@lang('Confirmation Alert!')
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure to return '){{optional($trade->receiverCurrency)->code}}@lang(' to seller ?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                    <form action="{{route('admin.trade.return',$trade->hash_slug)}}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Release Buyer Modal -->
    <div id="buyerReturn" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">@lang('Confirmation Alert!')
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure to release '){{optional($trade->receiverCurrency)->code}} ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                    <form action="{{route('admin.trade.release',$trade->hash_slug)}}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    Terms and Condition modal--}}
    <div class="modal fade" id="termsCondition" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Terms Of Trade')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>{{$trade->terms_of_trade}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{--    Payment Details modal--}}
    <div class="modal fade" id="paymentDetails" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Payment Details')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>{{$trade->payment_details}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        'use strict';
        let pushChatArea = new Vue({
            el: "#pushChatArea",
            data: {
                items: [],
                auth_id: "{{auth()->guard('admin')->id()}}",
                auth_model: "App\\Models\\Admin",
                message: ''
            },
            beforeMount() {
                this.getNotifications();
                this.pushNewItem();
            },
            methods: {
                getNotifications() {
                    let app = this;
                    axios.get("{{ route('admin.push.chat.show',$trade->hash_slug) }}")
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
                        console.log('update')
                    });
                },

                send() {
                    let app = this;
                    if (app.message.length == 0) {
                        Notiflix.Notify.Failure(`{{trans('Type your message')}}`);
                        return 0;
                    }


                    axios.post("{{ route('admin.push.chat.newMessage')}}", {
                        trade_id: "{{$trade->id}}",
                        message: app.message
                    }).then(function (res) {

                        if (res.data.errors) {
                            var err = res.data.errors;
                            for (const property in err) {
                                Notiflix.Notify.Failure(`${err[property]}`);
                            }
                        }

                        if (res.data.success == true) {
                            app.message = '';
                            Vue.nextTick(() => {
                                let messageDisplay = app.$refs.chatArea
                                messageDisplay.scrollTop = messageDisplay.scrollHeight
                            })
                        }
                    }).catch(function (error) {

                    });

                }
            }
        });
    </script>
@endpush
