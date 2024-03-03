<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Trade Details'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0">
        <div class="card-body">

            <div class="row no-gutters">
                <div class="col-lg-4 col-xl-4 border-right pr-2">
                    <div class="p-3 mb-4 shadow">
                        <div class="row d-flex justify-content-between px-2 mb-4">
                            <h5><i class="fa fa-info-circle"></i> <?php echo app('translator')->get('Trade Information'); ?></h5>
                            <?php if($trade->status == 3): ?>
                                <span class="badge badge-danger"><?php echo app('translator')->get('Cancelled'); ?></span>
                            <?php elseif($trade->status == 0): ?>
                                <span class="badge badge-warning"><?php echo app('translator')->get('Pending'); ?></span>
                            <?php elseif($trade->status == 1): ?>
                                <span class="badge badge-info"><?php echo app('translator')->get('Buyer Paid'); ?></span>
                            <?php elseif($trade->status == 4): ?>
                                <span class="badge badge-success"><?php echo app('translator')->get('Completed'); ?></span>
                            <?php elseif($trade->status == 5): ?>
                                <span class="badge badge-danger"><?php echo app('translator')->get('Reported'); ?></span>
                            <?php elseif($trade->status == 6 || $trade->status == 7): ?>
                                <span class="badge badge-info"><?php echo app('translator')->get('Escrow Funded'); ?></span>
                                   <?php elseif($trade->status == 8): ?>
                                <span class="badge badge-primary"><?php echo app('translator')->get('Resolved'); ?></span>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <ul class="rate">
                            <li class="mb-3"><?php echo app('translator')->get('Trade Number:'); ?> <span
                                    class="caption text-dark">#<?php echo e($trade->trade_number); ?></span>
                            </li>
                            <?php if($trade->status == 3): ?>
                                <li class="mb-3"><?php echo app('translator')->get('Canceled By'); ?> <span
                                        class="caption text-danger"><?php echo e($trade->cancelBy->username); ?></span>
                                </li>
                                <li class="mb-3"><?php echo app('translator')->get('Canceled At'); ?> <span
                                        class="caption text-danger"><?php echo e(dateTime($trade->cancel_at)); ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if($trade->status == 5): ?>
                                <li class="mb-3"><?php echo app('translator')->get('Reported By'); ?> <span
                                        class="caption text-danger"><?php echo e($trade->disputeBy->username); ?></span>
                                </li>
                                <li class="mb-3"><?php echo app('translator')->get('Reported At'); ?> <span
                                        class="caption text-danger"><?php echo e(dateTime($trade->dispute_at)); ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if($trade->type == 'buy'): ?>
                                <li class="mb-3"><?php echo app('translator')->get('Buyer Name:'); ?> <span
                                        class="caption"><a
                                            href="<?php echo e(route('admin.user-edit',$trade->owner_id)); ?>"><?php echo e(optional($trade->owner)->username); ?></a></span>
                                </li>
                                <li class="mb-3"><?php echo app('translator')->get('Seller Name:'); ?> <span
                                        class="caption"><a
                                            href="<?php echo e(route('admin.user-edit',$trade->sender_id)); ?>"><?php echo e(optional($trade->sender)->username); ?></a></span>
                                </li>
                            <?php else: ?>
                                <li class="mb-3"><?php echo app('translator')->get('Buyer Name:'); ?> <span
                                        class="caption"><a
                                            href="<?php echo e(route('admin.user-edit',$trade->sender_id)); ?>"><?php echo e(optional($trade->sender)->username); ?></a></span>
                                </li>
                                <li class="mb-3"><?php echo app('translator')->get('Seller Name:'); ?> <span
                                        class="caption"><a
                                            href="<?php echo e(route('admin.user-edit',$trade->owner_id)); ?>"><?php echo e(optional($trade->owner)->username); ?></a></span>
                                </li>
                            <?php endif; ?>
                            <li class="mb-3"><span class=""><?php echo app('translator')->get('Payment Method:'); ?></span><span
                                    class="caption"><?php $__currentLoopData = $trade->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="gateway-color"
                                              style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></span>
                            <li class="mb-3"><span class=""><?php echo app('translator')->get('Rate:'); ?></span><span
                                    class="caption"><?php echo e(getAmount($trade->rate+0,2)); ?> <?php echo e(optional($trade->currency)->code); ?>

                                                        /<?php echo e(optional($trade->receiverCurrency)->code); ?></span>
                            </li>
                            <li class="mb-3"><?php echo e(optional($trade->currency)->name); ?>: <span
                                    class="caption"><?php echo e(getAmount($trade->pay_amount,2)); ?> <?php echo e(optional($trade->currency)->code); ?></span>
                            </li>
                            <li class="mb-3"><?php echo e(optional($trade->receiverCurrency)->name); ?> <span
                                    class="caption"><?php echo e(getAmount($trade->receive_amount,8)); ?> <?php echo e(optional($trade->receiverCurrency)->code); ?></span>
                            </li>
                            <li class="mb-3"><?php echo app('translator')->get('Payment Window:'); ?> <span
                                    class="caption"><?php echo e($trade->payment_window); ?> <?php echo app('translator')->get('Minutes'); ?></span>
                            </li>
                            <?php if($trade->status == 4): ?>
                                <li class="mb-3"><?php echo app('translator')->get('Admin Charge:'); ?> <span
                                        class="caption"><?php echo e($trade->admin_charge); ?> <?php echo e(optional($trade->receiverCurrency)->code); ?></span>
                                </li>
                                <li class="mb-3"><?php echo app('translator')->get('Processing Time:'); ?> <span
                                        class="caption"><?php echo e($trade->processing_minutes); ?> <?php echo app('translator')->get('Minutes'); ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <?php if($trade->status == 5): ?>
                            <hr>
                            <div class="row d-flex justify-content-between px-2 mb-4">
                                <button class="btn btn-primary w-40" data-target="#sellerReturn" data-toggle="modal"><i
                                        class="fa fa-undo"></i> <?php echo app('translator')->get('In Favor of Seller'); ?>
                                </button>
                                <button class="btn btn-success w-40" data-target="#buyerReturn" data-toggle="modal"><i
                                        class="fa fa-check-circle"></i> <?php echo app('translator')->get('In Favor of Buyer'); ?></button>
                            </div>
                        <?php endif; ?>
                        <hr>
                        <div class="row d-flex justify-content-center px-2">
                            <a href="javascript:void(0)" data-target="#termsCondition" data-toggle="modal"><p
                                    class="mr-4"><i class="fa fa-info-circle"></i> <?php echo app('translator')->get('Terms of Trade'); ?></p></a>
                            <a href="javascript:void(0)" data-target="#paymentDetails" data-toggle="modal"><p><i
                                        class="fa fa-info-circle"></i> <?php echo app('translator')->get('Payments Details'); ?></p></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8  col-xl-8">
                    <?php if(!empty($persons)): ?>
                        <div class="p-3 mb-4 shadow">
                            <div class="report  justify-content-center " id="pushChatArea">
                                <audio id="myAudio">
                                    <source src="<?php echo e(asset('assets/admin/css/sound.mp3')); ?>" type="audio/mpeg">
                                </audio>
                                <div class="card ">
                                    <div
                                        class="adiv   justify-content-between align-items-center text-white p-2 d-flex">
                                        <p><i class="fas fa-users "></i> <?php echo e(trans('Conversation')); ?></p>
                                        <div class="d-flex user-chatlist">
                                            <?php if(!empty($persons)): ?>
                                                <?php $__empty_1 = true; $__currentLoopData = $persons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $person): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <div class="d-flex no-block align-items-center">
                                                        <a href="javascript:void(0)"
                                                           data-toggle="tooltip" data-placement="top" title=""
                                                           data-original-title="<?php echo e($person->username); ?>"
                                                           class="mr-1 position-relative">

                                                            <i class="batti position-absolute fa fa-circle text-<?php echo e(($person->lastSeen == true) ?'success':'warning'); ?> font-12"
                                                               title="<?php echo e(($person->lastSeen == true) ?'Online':'Away'); ?>"></i>
                                                            <img src="<?php echo e($person->imgPath); ?>"
                                                                 alt="user" class="rounded-circle " width="30"
                                                                 height="30">
                                                        </a>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
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
                                                    <span class="text-wa">{{item.description}}</span>
                                                    <span class="timmer">{{item.formatted_date}}</span>

                                                </div>
                                                <img
                                                    :src="item.chatable.imgPath"
                                                    width="30" height="30">
                                            </div>

                                            <div v-else class="d-flex flex-row justify-content-start p-3"
                                                 :title="item.chatable.username">
                                                <img :src="item.chatable.imgPath" width="30" height="30">
                                                <div class="chat ml-2 pt-1 pb-4  pl-2 pr-5 position-relative mw-130">
                                                    {{item.description}}
                                                    <span class="timmer">{{item.formatted_date}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($trade->status != 3 && $trade->status != 4 && $trade->status != 8): ?>
                                        <form @submit.prevent="send" enctype="multipart/form-data" method="post">
                                            <div class="writing-box d-flex justify-content-between align-items-center">
                                                <div class="input--group form-group px-3 ">
                                                    <input class="form--control type_msg" v-model.trim="message"
                                                           placeholder="<?php echo e(trans('Type your message')); ?>"/>
                                                </div>
                                                <div class="send text-center">
                                                    <button type="button" class="btn btn-success btn--success "
                                                            @click="send">
                                                        <i class="fas fa-paper-plane "></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
                    <h4 class="modal-title" id="primary-header-modalLabel"><?php echo app('translator')->get('Confirmation Alert!'); ?>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo app('translator')->get('Are you sure to return '); ?><?php echo e(optional($trade->receiverCurrency)->code); ?><?php echo app('translator')->get(' to seller ?'); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                    <form action="<?php echo e(route('admin.trade.return',$trade->hash_slug)); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Yes'); ?></button>
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
                    <h4 class="modal-title" id="primary-header-modalLabel"><?php echo app('translator')->get('Confirmation Alert!'); ?>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo app('translator')->get('Are you sure to release '); ?><?php echo e(optional($trade->receiverCurrency)->code); ?> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                    <form action="<?php echo e(route('admin.trade.release',$trade->hash_slug)); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Yes'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="termsCondition" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><?php echo app('translator')->get('Terms Of Trade'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p><?php echo e($trade->terms_of_trade); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span><?php echo app('translator')->get('Close'); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="paymentDetails" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><?php echo app('translator')->get('Payment Details'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p><?php echo e($trade->payment_details); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span><?php echo app('translator')->get('Close'); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
    <script>
        'use strict';
        let pushChatArea = new Vue({
            el: "#pushChatArea",
            data: {
                items: [],
                auth_id: "<?php echo e(auth()->guard('admin')->id()); ?>",
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
                    axios.get("<?php echo e(route('admin.push.chat.show',$trade->hash_slug)); ?>")
                        .then(function (res) {
                            app.items = res.data;
                        })
                },

                pushNewItem() {
                    let app = this;
                    // Pusher.logToConsole = true;
                    let pusher = new Pusher("<?php echo e(env('PUSHER_APP_KEY')); ?>", {
                        encrypted: true,
                        cluster: "<?php echo e(env('PUSHER_APP_CLUSTER')); ?>"
                    });

                    let channel = pusher.subscribe('offer-chat-notification.' + "<?php echo e($trade->hash_slug); ?>");
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
                        Notiflix.Notify.Failure(`<?php echo e(trans('Type your message')); ?>`);
                        return 0;
                    }


                    axios.post("<?php echo e(route('admin.push.chat.newMessage')); ?>", {
                        trade_id: "<?php echo e($trade->id); ?>",
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/trades/details.blade.php ENDPATH**/ ?>