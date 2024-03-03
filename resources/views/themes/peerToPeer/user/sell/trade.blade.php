@extends($theme.'layouts.user')
@section('title')
    @lang('New Trade Request')
@endsection
@section('content')
    <div id="feedback-app" v-cloak>
        <div class="container-fluid">
            <div class="main row">
                <section class="buy-coin-section">
                    <div class="row g-4">
                        <div class="col-12">
                            <h4>@lang('Sell') {{optional($advertisment->cryptoCurrency)->name}}
                                @lang('using')

                                <span class="">
                                @foreach($advertisment->gateways as $gateway)
                                        <span class="gateway-color text-base"
                                              style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                    @endforeach
                                </span>

                                @lang('with') {{optional($advertisment->fiatCurrency)->name}}
                                ( {{optional($advertisment->fiatCurrency)->code}} )</h4>


                            <p><a href="{{route('user.profile.page',$advertisment->user_id)}}"
                                  class="theme-color">{{optional($advertisment->user)->username}}</a> @lang('wishes to buy from you.')
                            </p>
                        </div>
                        <div class="col-lg-8">
                            <div class="rate">
                                <ul>
                                    <li>@lang('Rate:')
                                        <span>{{$advertisment->rate}} {{$advertisment->currency_rate}}</span>
                                    </li>
                                    <li>@lang('Payment Method:') <span>@foreach($advertisment->gateways as $gateway)
                                                <span class="gateway-color"
                                                      style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                            @endforeach</span></li>
                                    <li>@lang('User:') <a
                                            href="{{route('user.profile.page',$advertisment->user_id)}}"><span>{{optional($advertisment->user)->username}}</span></a>
                                    </li>
                                    <li class="text-warning">@lang('Trade Limits:')
                                        <span
                                            class="text-warning">{{$advertisment->minimum_limit}} - {{$advertisment->maximum_limit}}  {{optional($advertisment->fiatCurrency)->code}}</span>
                                    </li>
                                    <li>@lang('Payment Window:')
                                        <span>{{optional($advertisment->paymentWindow)->name}}</span></li>
                                </ul>
                            </div>
                            <div class="trade-request">
                                <h5 class="text-center mb-4">@lang('How much you wish to sell?')</h5>
                                <form action="{{route('user.sellCurrencies.trade.send')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="advertiseId" value="{{$advertisment->id}}">
                                    <div class="row justify-content-center align-items-center g-3">
                                        <div class="input-box col-md-6">
                                            <label for="">@lang('Buyer will pay')</label>
                                            <div class="input-group">
                                                <input class="form-control color-adjust pay" type="number" name="pay"
                                                       step="0.001"
                                                       placeholder="0.00" required/>
                                                <span>{{optional($advertisment->fiatCurrency)->code}}</span>
                                            </div>
                                            <span class="text-danger msg"></span>
                                            @error('pay')
                                            <p class="text-danger  mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="input-box col-md-6">
                                            <label for="">@lang('And Receive')</label>
                                            <div class="input-group">
                                                <input class="form-control color-adjust receive" type="text"
                                                       placeholder="0.00" readonly/>
                                                <span>{{optional($advertisment->cryptoCurrency)->code}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row  justify-content-center mt-4">
                                        <div class="col-md-5">
                                            <div class="input-box">
                                                <label for="">@lang('Select Payment Method')</label>
                                                <select class="form-select color-adjust paymentMethod"
                                                        aria-label="Default select example"
                                                        name="method_id" required>
                                                    <option selected disabled>@lang('Select Gateway')</option>
                                                    @if($advertisment->gateways)
                                                        @foreach($advertisment->gateways as $gateway)
                                                            <option
                                                                value="{{$gateway->id}}"
                                                                @if(session()->get('payment-method') == $gateway->id) selected @endif>{{$gateway->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('method')
                                                <p class="text-danger  mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class=" col-md-5">
                                            <div class="input-box">
                                                <label for="">@lang('Select Information')</label>
                                                <select class="form-select color-adjust infoOption"
                                                        aria-label="Default select example"
                                                        name="information_id" >
                                                </select>

                                                @error('information')
                                                <span class="text-danger  mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class=" col-md-2 ">
                                            <div class="input-box mt-3">
                                            <a href="{{route('user.sellCurrencies.gatewayInfo',$advertisment->id)}}"
                                               class="btn btn-success mt-4"><i
                                                    class="fa fa-check-circle"></i> @lang('Add Method')</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center align-items-center mt-4">
                                        <div class="input-box col-12">
                                             <textarea
                                                 class="form-control color-adjust"
                                                 cols="30"
                                                 rows="3"
                                                 name="message"
                                                 placeholder="@lang('Write your contact message and other info for the trade here')"
                                                 required>
                                             </textarea>
                                            @error('message')
                                            <p class="text-danger  mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="input-box col-12 mt-3">
                                            <button class="btn-custom w-100">@lang('Send trade request')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="side-bar">
                                <div class="user-box">
                                    <div class="d-flex mb-3">
                                        <img
                                            src="{{getFile(config('location.user.path').optional($advertisment->user)->image)}}"
                                            alt="..."/>
                                        <div>
                                            <h5>{{optional($advertisment->user)->username}}</h5>
                                            <p>
                                                <span><i
                                                        class="far fa-thumbs-up"></i> {{$advertisment->like_count}}</span>
                                                <span><i class="far fa-thumbs-down "></i> {{$advertisment->dislike_count}}</span>
                                            </p>
                                            <p>
                                                <i class="far fa-globe-americas "></i> {{optional($advertisment->user)->address}}
                                            </p>
                                        </div>
                                    </div>
                                    @if(optional($advertisment->user->email_verification == 1))
                                        <p><i class="far fa-check-circle"></i> @lang('Email Address Verified')</p>
                                    @endif
                                    @if(optional($advertisment->user->sms_verification == 1))
                                        <p><i class="far fa-check-circle"></i> @lang('Mobile Number Verified')</p>
                                    @endif
                                    @if(optional($advertisment->user->identity_verify == 2))
                                        <p><i class="far fa-check-circle"></i> @lang('Identity Verified')</p>
                                    @endif
                                    @if(optional($advertisment->user->address_verify == 2))
                                        <p><i class="far fa-check-circle"></i> @lang('Address Verified')</p>
                                    @endif
                                </div>
                                <div>
                                    <h5><i class="fal fa-file-spreadsheet"></i> @lang('Terms')
                                    </h5>
                                    <p>
                                        {{$advertisment->terms_of_trade}}
                                    </p>
                                </div>
                                <div>
                                    <h5><i class="fal fa-file-spreadsheet"></i> @lang('Payment details fo this trade')
                                    </h5>
                                    <p>
                                        {{$advertisment->payment_details}}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="feedback">
                                <div class="d-flex justify-content-start">
                                    <h5>@lang('Feedbacks on This Advertisement')</h5>
                                    @if($feedbackable == 'true' && !$advertisment->userfeedbacks)
                                        <a href="javascript:void(0)" data-bs-target="#feedbackModal"
                                           data-bs-toggle="modal"
                                           title="@lang('Add Feedback')" class="theme-color" @click="makeEmptyItem">
                                            <i class="fas fa-plus-circle ms-2 customi"></i></a>
                                    @endif
                                </div>
                                <div id="reviews" class="reviews">
                                    <div class="customer-review">
                                        <div class="review-box" v-for="(obj, index) in item.feedArr">
                                            <div class="text">
                                                <img
                                                    :src="obj.reviewer.imgPath"
                                                    alt="..."/>
                                                <span class="name">@{{obj.reviewer.username}}</span>
                                                <span class="name" v-if="obj.position =='like'"><i
                                                        class="fa fa-thumbs-up fa-2x like"
                                                        aria-hidden="true"></i></span>
                                                <span class="name" v-if="obj.position =='dislike'"><i
                                                        class="fa fa-thumbs-down fa-2x like"
                                                        aria-hidden="true"></i></span>
                                                <p class="mt-3">
                                                    @{{obj.feedback}}
                                                </p>
                                            </div>
                                            <div class="review-date">
                                                <br/>
                                                <span
                                                    class="date"> @{{obj.date_formatted}}</span>
                                            </div>
                                        </div>
                                        <div class="text-center mt-5" v-if="item.feedArr.length == 0">
                                            <i class="fal fa-file-times"></i>
                                            <p>@lang('No feedback yet!')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        @if($feedbackable == 'true' && !$advertisment->userfeedbacks)
            <!-- Modal -->
            <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="editModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">@lang('Add Feedback')</h5>
                            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>
                        <form>
                            @csrf
                            <div class="modal-body">
                                <div class="">
                                    <p>
                                        @lang('Writing your own feedback may help others
                                        to continue trading')
                                    </p>
                                </div>
                                <div class="input-box">
                                    <div class="d-flex justify-content-between">
                                        <label
                                            for="exampleFormControlTextarea1"
                                            class="form-label">@lang('Your message')</label>
                                        <div class="rating">
                                            <!-- Thumbs up -->
                                            <div class="like grow">
                                                <i class="fa fa-thumbs-up fa-3x like active"
                                                   @click="thumbUpDown('like')"
                                                   aria-hidden="true"></i>
                                            </div>
                                            <!-- Thumbs down -->
                                            <div class="dislike grow">
                                                <i class="fa fa-thumbs-down fa-3x like" @click="thumbUpDown('dislike')"
                                                   aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea
                                        class="form-control color-adjust"
                                        id="exampleFormControlTextarea1"
                                        rows="5"
                                        name="feedback"
                                        v-model="item.feedback"
                                        placeholder="@lang('Opinion')"
                                    ></textarea>
                                    <span class="text-danger">@{{error.feedbackError}}</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-custom btn2"
                                        data-bs-dismiss="modal">@lang('Close')</button>
                                <button type="button" @click.prevent="addFeedback()"
                                        class="btn-custom">@lang('Add')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
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
        var gatewayId = $('.paymentMethod option:selected').val();
        fetchInfo(gatewayId);
        var minLimit = '{{$advertisment->minimum_limit}}'
        var maxLimit = '{{$advertisment->maximum_limit}}', currency = '{{optional($advertisment->fiatCurrency)->code}}'
        var newApp = new Vue({
            el: "#feedback-app",
            data: {
                item: {
                    feedback: "",
                    adId: '', position: '',
                    feedArr: [],
                },
                showModal: false,
                error: {
                    feedbackError: ''
                }
            },
            mounted() {
                let _this = this;
                _this.item.adId = "{{$advertisment->id}}"
                _this.item.feedArr = @json($advertisment->feedbacks);
                _this.item.position = 'like';
            },
            methods: {
                toggleModal() {
                    this.showModal = !this.showModal;
                },
                thumbUpDown(position) {
                    this.item.position = position;
                },
                addFeedback() {
                    let item = this.item;
                    this.makeError();
                    this.feedback = this.item.feedback;
                    this.position = this.item.position;
                    axios.post("{{ route('user.advertisements.feedback') }}", this.item)
                        .then(function (response) {
                            if (response.data.status == 'success') {
                                item.feedArr.unshift({
                                    feedback: response.data.data.feedback,
                                    reviewer: response.data.data.reviewer,
                                    position: response.data.data.position,
                                    date_formatted: response.data.data.date_formatted,
                                });
                                Notiflix.Notify.Success("Added Feedback");
                                $('#feedbackModal').modal('hide');
                                this.makeEmptyItem();
                                this.showModal = false;
                                return 0;
                            } else {
                                Notiflix.Notify.Failure("" + response.data.msg);
                                this.showModal = false;
                                $('#feedbackModal').modal('hide');
                            }
                        })
                        .catch(function (error) {
                            let errors = error.response.data;
                            errors = errors.errors
                        });
                },
                makeError() {
                    if (!this.item.feedback) {
                        this.error.feedbackError = "Your Feedback field is required"
                    }
                },
                makeEmptyItem() {
                    this.item.feedback = "";
                }
            }
        })

        $(document).on('change', '.paymentMethod', function () {
            var gatewayId = $(this).val();
            fetchInfo(gatewayId);
        });

        function fetchInfo(gatewayId) {
            var _sessionInfo = "{{session()->get('user_payment_infoId')?? null}}";
            $.ajax({
                url: "{{ route('user.sellCurrencies.FetchPaymentInfo') }}",
                method: 'post',
                data: {
                    gatewayId: gatewayId,
                },
                success: function (response) {
                    console.log(response)
                    $('.infoOption').html(`<option value="">@lang("Select Information")</option>`);
                    $.each(response.info, function (key, value) {
                        $(".infoOption").append(`<option value="${value.id}" ${(value.id == _sessionInfo)?'selected':''} >${value.information}</option>`);
                    });
                }
            });
        }


        var rate = '{{$advertisment->rate}}'
        $(document).on('keyup', '.pay', function (e) {
            $('.msg').text('')
            $value = $(this).val();
            if (parseFloat($value) > parseFloat(maxLimit)) {
                $('.msg').text(`Trade Maximum limit ${maxLimit} ${currency}`)
            }
            if (parseFloat($value) < parseFloat(minLimit)) {
                $('.msg').text(`Trade Minimum limit ${minLimit} ${currency}`)
            }
            calPrice($(this).val());
        });

        function calPrice(inputPrice = null) {
            var receive = parseFloat(1 * inputPrice / rate).toFixed(8);
            $('.receive').val(receive);
        }

        $('.like, .dislike').on('click', function () {
            event.preventDefault();
            $('.active').removeClass('active');
            $(this).addClass('active');
        });
    </script>
@endpush
