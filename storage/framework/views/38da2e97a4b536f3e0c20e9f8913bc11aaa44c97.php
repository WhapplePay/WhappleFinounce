<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('New Trade Request'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div id="feedback-app" v-cloak>
        <div class="container-fluid">
            <div class="main row">
                <section class="buy-coin-section">
                    <div class="row g-4">
                        <div class="col-12">
                            <h4><?php echo app('translator')->get('Sell'); ?> <?php echo e(optional($advertisment->cryptoCurrency)->name); ?>

                                <?php echo app('translator')->get('using'); ?>

                                <span class="">
                                <?php $__currentLoopData = $advertisment->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="gateway-color text-base"
                                              style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </span>

                                <?php echo app('translator')->get('with'); ?> <?php echo e(optional($advertisment->fiatCurrency)->name); ?>

                                ( <?php echo e(optional($advertisment->fiatCurrency)->code); ?> )</h4>


                            <p><a href="<?php echo e(route('user.profile.page',$advertisment->user_id)); ?>"
                                  class="theme-color"><?php echo e(optional($advertisment->user)->username); ?></a> <?php echo app('translator')->get('wishes to buy from you.'); ?>
                            </p>
                        </div>
                        <div class="col-lg-8">
                            <div class="rate">
                                <ul>
                                    <li><?php echo app('translator')->get('Rate:'); ?>
                                        <span><?php echo e($advertisment->rate); ?> <?php echo e($advertisment->currency_rate); ?></span>
                                    </li>
                                    <li><?php echo app('translator')->get('Payment Method:'); ?> <span><?php $__currentLoopData = $advertisment->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="gateway-color"
                                                      style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></span></li>
                                    <li><?php echo app('translator')->get('User:'); ?> <a
                                            href="<?php echo e(route('user.profile.page',$advertisment->user_id)); ?>"><span><?php echo e(optional($advertisment->user)->username); ?></span></a>
                                    </li>
                                    <li class="text-warning"><?php echo app('translator')->get('Trade Limits:'); ?>
                                        <span
                                            class="text-warning"><?php echo e($advertisment->minimum_limit); ?> - <?php echo e($advertisment->maximum_limit); ?>  <?php echo e(optional($advertisment->fiatCurrency)->code); ?></span>
                                    </li>
                                    <li><?php echo app('translator')->get('Payment Window:'); ?>
                                        <span><?php echo e(optional($advertisment->paymentWindow)->name); ?></span></li>
                                </ul>
                            </div>
                            <div class="trade-request">
                                <h5 class="text-center mb-4"><?php echo app('translator')->get('How much you wish to sell?'); ?></h5>
                                <form action="<?php echo e(route('user.sellCurrencies.trade.send')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="advertiseId" value="<?php echo e($advertisment->id); ?>">
                                    <div class="row justify-content-center align-items-center g-3">
                                        <div class="input-box col-md-6">
                                            <label for=""><?php echo app('translator')->get('Buyer will pay'); ?></label>
                                            <div class="input-group">
                                                <input class="form-control color-adjust pay" type="number" name="pay"
                                                       step="0.001"
                                                       placeholder="0.00" required/>
                                                <span><?php echo e(optional($advertisment->fiatCurrency)->code); ?></span>
                                            </div>
                                            <span class="text-danger msg"></span>
                                            <?php $__errorArgs = ['pay'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="input-box col-md-6">
                                            <label for=""><?php echo app('translator')->get('And Receive'); ?></label>
                                            <div class="input-group">
                                                <input class="form-control color-adjust receive" type="text"
                                                       placeholder="0.00" readonly/>
                                                <span><?php echo e(optional($advertisment->cryptoCurrency)->code); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row  justify-content-center mt-4">
                                        <div class="col-md-5">
                                            <div class="input-box">
                                                <label for=""><?php echo app('translator')->get('Select Payment Method'); ?></label>
                                                <select class="form-select color-adjust paymentMethod"
                                                        aria-label="Default select example"
                                                        name="method_id" required>
                                                    <option selected disabled><?php echo app('translator')->get('Select Gateway'); ?></option>
                                                    <?php if($advertisment->gateways): ?>
                                                        <?php $__currentLoopData = $advertisment->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option
                                                                value="<?php echo e($gateway->id); ?>"
                                                                <?php if(session()->get('payment-method') == $gateway->id): ?> selected <?php endif; ?>><?php echo e($gateway->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </select>
                                                <?php $__errorArgs = ['method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                        <div class=" col-md-5">
                                            <div class="input-box">
                                                <label for=""><?php echo app('translator')->get('Select Information'); ?></label>
                                                <select class="form-select color-adjust infoOption"
                                                        aria-label="Default select example"
                                                        name="information_id" >
                                                </select>

                                                <?php $__errorArgs = ['information'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-danger  mt-1"><?php echo e($message); ?></span>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>

                                        </div>
                                        <div class=" col-md-2 ">
                                            <div class="input-box mt-3">
                                            <a href="<?php echo e(route('user.sellCurrencies.gatewayInfo',$advertisment->id)); ?>"
                                               class="btn btn-success mt-4"><i
                                                    class="fa fa-check-circle"></i> <?php echo app('translator')->get('Add Method'); ?></a>
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
                                                 placeholder="<?php echo app('translator')->get('Write your contact message and other info for the trade here'); ?>"
                                                 required>
                                             </textarea>
                                            <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="input-box col-12 mt-3">
                                            <button class="btn-custom w-100"><?php echo app('translator')->get('Send trade request'); ?></button>
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
                                            src="<?php echo e(getFile(config('location.user.path').optional($advertisment->user)->image)); ?>"
                                            alt="..."/>
                                        <div>
                                            <h5><?php echo e(optional($advertisment->user)->username); ?></h5>
                                            <p>
                                                <span><i
                                                        class="far fa-thumbs-up"></i> <?php echo e($advertisment->like_count); ?></span>
                                                <span><i class="far fa-thumbs-down "></i> <?php echo e($advertisment->dislike_count); ?></span>
                                            </p>
                                            <p>
                                                <i class="far fa-globe-americas "></i> <?php echo e(optional($advertisment->user)->address); ?>

                                            </p>
                                        </div>
                                    </div>
                                    <?php if(optional($advertisment->user->email_verification == 1)): ?>
                                        <p><i class="far fa-check-circle"></i> <?php echo app('translator')->get('Email Address Verified'); ?></p>
                                    <?php endif; ?>
                                    <?php if(optional($advertisment->user->sms_verification == 1)): ?>
                                        <p><i class="far fa-check-circle"></i> <?php echo app('translator')->get('Mobile Number Verified'); ?></p>
                                    <?php endif; ?>
                                    <?php if(optional($advertisment->user->identity_verify == 2)): ?>
                                        <p><i class="far fa-check-circle"></i> <?php echo app('translator')->get('Identity Verified'); ?></p>
                                    <?php endif; ?>
                                    <?php if(optional($advertisment->user->address_verify == 2)): ?>
                                        <p><i class="far fa-check-circle"></i> <?php echo app('translator')->get('Address Verified'); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h5><i class="fal fa-file-spreadsheet"></i> <?php echo app('translator')->get('Terms'); ?>
                                    </h5>
                                    <p>
                                        <?php echo e($advertisment->terms_of_trade); ?>

                                    </p>
                                </div>
                                <div>
                                    <h5><i class="fal fa-file-spreadsheet"></i> <?php echo app('translator')->get('Payment details fo this trade'); ?>
                                    </h5>
                                    <p>
                                        <?php echo e($advertisment->payment_details); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="feedback">
                                <div class="d-flex justify-content-start">
                                    <h5><?php echo app('translator')->get('Feedbacks on This Advertisement'); ?></h5>
                                    <?php if($feedbackable == 'true' && !$advertisment->userfeedbacks): ?>
                                        <a href="javascript:void(0)" data-bs-target="#feedbackModal"
                                           data-bs-toggle="modal"
                                           title="<?php echo app('translator')->get('Add Feedback'); ?>" class="theme-color" @click="makeEmptyItem">
                                            <i class="fas fa-plus-circle ms-2 customi"></i></a>
                                    <?php endif; ?>
                                </div>
                                <div id="reviews" class="reviews">
                                    <div class="customer-review">
                                        <div class="review-box" v-for="(obj, index) in item.feedArr">
                                            <div class="text">
                                                <img
                                                    :src="obj.reviewer.imgPath"
                                                    alt="..."/>
                                                <span class="name">{{obj.reviewer.username}}</span>
                                                <span class="name" v-if="obj.position =='like'"><i
                                                        class="fa fa-thumbs-up fa-2x like"
                                                        aria-hidden="true"></i></span>
                                                <span class="name" v-if="obj.position =='dislike'"><i
                                                        class="fa fa-thumbs-down fa-2x like"
                                                        aria-hidden="true"></i></span>
                                                <p class="mt-3">
                                                    {{obj.feedback}}
                                                </p>
                                            </div>
                                            <div class="review-date">
                                                <br/>
                                                <span
                                                    class="date"> {{obj.date_formatted}}</span>
                                            </div>
                                        </div>
                                        <div class="text-center mt-5" v-if="item.feedArr.length == 0">
                                            <i class="fal fa-file-times"></i>
                                            <p><?php echo app('translator')->get('No feedback yet!'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <?php if($feedbackable == 'true' && !$advertisment->userfeedbacks): ?>
            <!-- Modal -->
            <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="editModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel"><?php echo app('translator')->get('Add Feedback'); ?></h5>
                            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>
                        <form>
                            <?php echo csrf_field(); ?>
                            <div class="modal-body">
                                <div class="">
                                    <p>
                                        <?php echo app('translator')->get('Writing your own feedback may help others
                                        to continue trading'); ?>
                                    </p>
                                </div>
                                <div class="input-box">
                                    <div class="d-flex justify-content-between">
                                        <label
                                            for="exampleFormControlTextarea1"
                                            class="form-label"><?php echo app('translator')->get('Your message'); ?></label>
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
                                        placeholder="<?php echo app('translator')->get('Opinion'); ?>"
                                    ></textarea>
                                    <span class="text-danger">{{error.feedbackError}}</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-custom btn2"
                                        data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                                <button type="button" @click.prevent="addFeedback()"
                                        class="btn-custom"><?php echo app('translator')->get('Add'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <?php if($errors->any()): ?>
        <?php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        ?>
        <script>
            "use strict";
            <?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            Notiflix.Notify.Failure("<?php echo e(trans($error)); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
    <?php endif; ?>
    <script>
        'use script'
        var gatewayId = $('.paymentMethod option:selected').val();
        fetchInfo(gatewayId);
        var minLimit = '<?php echo e($advertisment->minimum_limit); ?>'
        var maxLimit = '<?php echo e($advertisment->maximum_limit); ?>', currency = '<?php echo e(optional($advertisment->fiatCurrency)->code); ?>'
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
                _this.item.adId = "<?php echo e($advertisment->id); ?>"
                _this.item.feedArr = <?php echo json_encode($advertisment->feedbacks, 15, 512) ?>;
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
                    axios.post("<?php echo e(route('user.advertisements.feedback')); ?>", this.item)
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
            var _sessionInfo = "<?php echo e(session()->get('user_payment_infoId')?? null); ?>";
            $.ajax({
                url: "<?php echo e(route('user.sellCurrencies.FetchPaymentInfo')); ?>",
                method: 'post',
                data: {
                    gatewayId: gatewayId,
                },
                success: function (response) {
                    console.log(response)
                    $('.infoOption').html(`<option value=""><?php echo app('translator')->get("Select Information"); ?></option>`);
                    $.each(response.info, function (key, value) {
                        $(".infoOption").append(`<option value="${value.id}" ${(value.id == _sessionInfo)?'selected':''} >${value.information}</option>`);
                    });
                }
            });
        }


        var rate = '<?php echo e($advertisment->rate); ?>'
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/sell/trade.blade.php ENDPATH**/ ?>