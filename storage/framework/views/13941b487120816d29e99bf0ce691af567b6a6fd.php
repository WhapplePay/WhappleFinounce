<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('New Trade Request'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
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
                            <?php if($trade->owner_id == auth()->user()->id): ?>
                                <h4>
                                    <?php echo e($trade->type == 'sell'?'Selling':'Buying'); ?> <?php echo e(getAmount($trade->receive_amount,8)); ?> <?php echo e(optional($trade->receiverCurrency)->code); ?>

                                    <?php echo app('translator')->get('FOR'); ?> <?php echo e(getAmount($trade->pay_amount+0)); ?> <?php echo e(optional($trade->currency)->code); ?> <?php echo app('translator')->get('via'); ?>
                                    <div class="flex-wrap mt-1">
                                        <?php $__currentLoopData = $trade->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="gateway-color"
                                                  style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </h4>
                            <?php else: ?>
                                <h4>
                                    <?php echo e($trade->type == 'sell'?'Buying':'Selling'); ?> <?php echo e(getAmount($trade->receive_amount,8)); ?> <?php echo e(optional($trade->receiverCurrency)->code); ?> <?php echo app('translator')->get('FOR'); ?>
                                    <?php echo e(getAmount($trade->pay_amount+0)); ?> <?php echo e(optional($trade->currency)->code); ?> <?php echo app('translator')->get('via'); ?>
                                    <div class="flex-wrap mt-1">
                                        <?php $__currentLoopData = $trade->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="gateway-color"
                                                  style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </h4>
                            <?php endif; ?>
                            <h5><?php echo app('translator')->get('Exchange Rate:'); ?> <?php echo e(getAmount($trade->rate+0,2)); ?> <?php echo e(optional($trade->currency)->code); ?>

                                /<?php echo e(optional($trade->receiverCurrency)->code); ?></h5>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="inbox-wrapper">
                            <!-- top bar -->
                            <div class="top-bar">
                                <div>
                                    <?php if($trade->owner_id == auth()->user()->id): ?>
                                        <img class="user img-fluid"
                                             src="<?php echo e(getFile(config('location.user.path').optional($trade->sender)->image)); ?>"
                                             alt="..."/>
                                        <i class="online position-absolute fa fa-circle text-<?php echo e((optional($trade->sender)->lastSeen == true) ?trans('success'):trans('warning')); ?>"
                                           title="<?php echo e((optional($trade->sender)->lastSeen == true) ?trans('Online'):trans('Away')); ?>"></i>
                                    <?php else: ?>
                                        <img class="user img-fluid"
                                             src="<?php echo e(getFile(config('location.user.path').optional($trade->owner)->image)); ?>"
                                             alt="..."/>
                                        <i class="online position-absolute fa fa-circle text-<?php echo e((optional($trade->owner)->lastSeen == true) ?trans('success'):trans('warning')); ?>"
                                           title="<?php echo e((optional($trade->owner)->lastSeen == true) ?trans('Online'):trans('Away')); ?>"></i>
                                    <?php endif; ?>
                                    <?php if($trade->owner_id == auth()->user()->id): ?>
                                        <a href="<?php echo e(route('user.profile.page',optional($trade->sender)->id)); ?>"><span
                                                class="name"><?php echo app('translator')->get(optional($trade->sender)->username); ?></span></a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('user.profile.page',optional($trade->owner)->id)); ?>"><span
                                                class="name"><?php echo app('translator')->get(optional($trade->owner)->username); ?></span></a>
                                    <?php endif; ?>
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
                                                <p>{{item.description}}</p>
                                            </div>
                                            <span class="time">{{item.formatted_date}}</span>
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
                                            <span class="time">{{item.formatted_date}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- typing area -->
                            <?php if($trade->status != 3 && $trade->status != 4 && $trade->status != 8): ?>
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
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="dispute-trade">
                            <div class="d-flex justify-content-between mb-4">
                                <span>#<?php echo e($trade->trade_number); ?></span>
                                <?php if($trade->status == 3): ?>
                                    <span class="current-status cancel"><?php echo app('translator')->get('Cancelled'); ?></span>
                                <?php elseif($trade->status == 1): ?>
                                    <span class="current-status paid"><?php echo app('translator')->get('Buyer Paid'); ?></span>
                                <?php elseif($trade->status == 4): ?>
                                    <span class="current-status complete"><?php echo app('translator')->get('Completed'); ?></span>
                                <?php elseif($trade->status == 5): ?>
                                    <span class="current-status reported"><?php echo app('translator')->get('Reported'); ?></span>
                                <?php elseif($trade->status == 0): ?>
                                    <span class="current-status warning"><?php echo app('translator')->get('Pending'); ?></span>
                                <?php elseif($trade->status == 6 || $trade->status == 7): ?>
                                    <span class="badge badge-info"><?php echo app('translator')->get('Escrow Funded'); ?></span>
                                <?php elseif($trade->status == 8): ?>
                                    <span class="current-status complete"><?php echo app('translator')->get('Resolved'); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if($trade->status != 3 && $trade->status != 4 && $trade->status != 8): ?>
                                <?php if($trade->owner_id != auth()->user()->id): ?>
                                    <?php if($trade->status == 0 && $trade->type == 'sell'): ?>
                                        <p class="text-danger text-center"><?php echo app('translator')->get('Please pay'); ?> <?php echo e(getAmount($trade->pay_amount,2)); ?> <?php echo e(optional($trade->currency)->code); ?> <?php echo app('translator')->get('using'); ?><?php $__currentLoopData = $trade->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="gateway-color"
                                                      style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></p>
                                        <p> <?php echo e(getAmount($trade->receive_amount,8)); ?> <?php echo e(optional($trade->receiverCurrency)->code); ?> <?php echo app('translator')->get('will be added to your wallet after
                                            confirmation about the
                                            payment.'); ?></p>
                                    <?php endif; ?>
                                    <?php if($trade->status == 0 && $trade->type == 'buy'): ?>
                                        <p><?php echo app('translator')->get('Once the buyer has confirmed your payment then'); ?> <span
                                                class="theme-color"><?php echo e(getAmount($trade->receive_amount,8)); ?> <?php echo e(optional($trade->receiverCurrency)->code); ?></span> <?php echo app('translator')->get('will be available for release.'); ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if($trade->status == 5): ?>
                                        <p class="bg-primary-dark text-danger"><?php echo app('translator')->get('This trade is Reported by'); ?>
                                            <span class="text-warning"><?php echo e(optional($trade->disputeBy)->username); ?></span>
                                            <?php echo app('translator')->get('. Please wait for the system response.'); ?></p>
                                    <?php endif; ?>
                                    <?php if($trade->status == 1 && \Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) > \Carbon\Carbon::now() && $trade->type == 'sell'): ?>
                                        <p class="bg-primary-dark"><?php echo app('translator')->get('You can dispute this trade after'); ?></p>
                                    <?php endif; ?>
                                    <?php if($trade->time_remaining && \Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) > \Carbon\Carbon::now()): ?>
                                        <p id="counter" class="theme-color"></p>
                                        <script>getCountDown("counter", <?php echo e(\Carbon\Carbon::parse($trade->time_remaining)->diffInSeconds()); ?>);</script>
                                    <?php endif; ?>
                                    <?php if($trade->type == 'sell'): ?>
                                    <div class="d-flex mb-4">
                                        <button class="btn-custom w-50 mx-1 dispute-btn" data-bs-target="#cancelModal" data-bs-toggle="modal">
                                            <i class="fal fa-times-circle"></i> <?php echo app('translator')->get('Cancel'); ?>
                                        </button>
                                        <?php if($trade->status == 1): ?> 
                                            
                                        <?php elseif(!in_array($trade->status, [0,1,5]) &&$hasRunningTrades): ?>
                                            <p>Another trade is ongoing. Please be patient</p>
                                        <?php elseif(!$hasRunningTrades ): ?>
                                            <button class="btn-custom w-50 mx-1 release-btn" data-bs-target="#paidModal" data-bs-toggle="modal">
                                                <i class="fal fa-check-circle"></i> <?php echo app('translator')->get('I have paid'); ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <p>You cannot place a new trade for the same advertisement while there is another running trade.</p>
                                <?php endif; ?>
                                
                                <?php if($trade->status == 1 && $trade->type == 'buy'): ?>
                                        <div class="d-flex mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#disputeModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> <?php echo app('translator')->get('Dispute'); ?>
                                            </button>
                                            <button class="btn-custom w-50 mx-1 release-btn"
                                                    data-bs-target="#releaseModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-check-circle text-white"></i> <?php echo app('translator')->get('Payment Received'); ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(\Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) < \Carbon\Carbon::now() && $trade->status ==1 && $trade->type == 'sell'): ?>
                                        <div class="d-flex justify-content-center mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#disputeModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> <?php echo app('translator')->get('Dispute'); ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if($trade->status == 1 && \Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) > \Carbon\Carbon::now() &&$trade->type == 'buy'): ?>
                                        <p class="bg-primary-dark"><?php echo app('translator')->get('You can dispute this trade after'); ?></p>
                                    <?php endif; ?>
                                    <?php if($trade->time_remaining && \Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) > \Carbon\Carbon::now()): ?>
                                        <p id="counter" class="theme-color"></p>
                                        <script>getCountDown("counter", <?php echo e(\Carbon\Carbon::parse($trade->time_remaining)->diffInSeconds()); ?>);</script>
                                    <?php endif; ?>
                                    <?php if($trade->status == 0 && $trade->type == 'buy'): ?>
                                        <p class="text-danger text-center"><?php echo app('translator')->get('Please pay'); ?> <?php echo e(getAmount($trade->pay_amount,2)); ?> <?php echo e(optional($trade->currency)->code); ?> <?php echo app('translator')->get('using'); ?>
                                        <div class="d-flex flex-wrap">
                                            <?php $__currentLoopData = $trade->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="gateway-color"
                                                      style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        </p>
                                        <p> <?php echo e(getAmount($trade->receive_amount,8)); ?> <?php echo e(optional($trade->receiverCurrency)->code); ?> <?php echo app('translator')->get('will be added to your wallet after
                                            confirmation about the
                                            payment.'); ?></p>
                                        <div class="d-flex mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#cancelModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> <?php echo app('translator')->get('Cancel'); ?>
                                            </button>
                                            <button class="btn-custom w-50 mx-1 release-btn"
                                                    data-bs-target="#paidModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-check-circle"></i> <?php echo app('translator')->get('I have paid'); ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(\Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window+$configure->trade_extra_time) < \Carbon\Carbon::now() && $trade->status ==1 && $trade->type == 'buy'): ?>
                                        <div class="d-flex justify-content-center mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#disputeModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> <?php echo app('translator')->get('Disputes'); ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($trade->status == 5): ?>
                                        <p class="bg-primary-dark text-danger"><?php echo app('translator')->get('This trade is Reported by'); ?>
                                            <span class="text-warning"><?php echo e(optional($trade->disputeBy)->username); ?></span>
                                            <?php echo app('translator')->get('Please wait for the system response.'); ?></p>
                                    <?php else: ?>
                                        <?php if($trade->type != 'buy' && $trade->status != 0 ): ?>
                                            <p class="bg-primary-dark"><?php echo app('translator')->get('The buyer can dispute anytime this trade after countdown time.'); ?></p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if($trade->status == 1 && $trade->type == 'sell'): ?>
                                        <div class="d-flex mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#disputeModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> <?php echo app('translator')->get('Dispute'); ?>
                                            </button>
                                            <button class="btn-custom w-50 mx-1 release-btn"
                                                    data-bs-target="#releaseModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-check-circle"></i> <?php echo app('translator')->get('release'); ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($trade->status == 0 && $trade->type == 'sell'): ?>
                                        <div class="d-flex justify-content-center mb-4">
                                            <button class="btn-custom w-50 mx-1 dispute-btn"
                                                    data-bs-target="#cancelModal"
                                                    data-bs-toggle="modal">
                                                <i class="fal fa-times-circle"></i> <?php echo app('translator')->get('Cancel'); ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
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
                                            <?php echo app('translator')->get('trade information'); ?>
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
                                                    <?php if($trade->type == 'buy'): ?>
                                                        <li><?php echo app('translator')->get('Buyer Name:'); ?> <span
                                                                class="caption"><?php echo e(optional($trade->owner)->username); ?></span>
                                                        </li>
                                                        <li><?php echo app('translator')->get('Seller Name:'); ?> <span
                                                                class="caption"><?php echo e(optional($trade->sender)->username); ?></span>
                                                        </li>
                                                    <?php else: ?>
                                                        <li><?php echo app('translator')->get('Buyer Name:'); ?> <span
                                                                class="caption"><?php echo e(optional($trade->sender)->username); ?></span>
                                                        </li>
                                                        <li><?php echo app('translator')->get('Seller Name:'); ?> <span
                                                                class="caption"><?php echo e(optional($trade->owner)->username); ?></span>
                                                        </li>
                                                    <?php endif; ?>

                                                    <li><span class=""><?php echo app('translator')->get('Rate:'); ?></span><span
                                                            class="caption"><?php echo e(getAmount($trade->rate,2)); ?> <?php echo e(optional($trade->currency)->code); ?>

                                                        /<?php echo e(optional($trade->receiverCurrency)->code); ?></span>
                                                    </li>
                                                    <li><?php echo e(optional($trade->currency)->name); ?>: <span
                                                            class="caption"><?php echo e(getAmount($trade->pay_amount,2)); ?> <?php echo e(optional($trade->currency)->code); ?></span>
                                                    </li>
                                                    <li><?php echo e(optional($trade->receiverCurrency)->name); ?> <span
                                                            class="caption"><?php echo e(getAmount($trade->receive_amount,8)); ?> <?php echo e(optional($trade->receiverCurrency)->code); ?></span>
                                                    </li>

                                                    <li><?php echo app('translator')->get('Payment Method:'); ?> <span
                                                            class="caption"><?php echo e($trade->payment_window); ?> <?php echo app('translator')->get('Minutes'); ?></span>
                                                    </li>
                                                    <?php if($trade->status == 4): ?>
                                                        <li><?php echo app('translator')->get('Admin Charge:'); ?> <span
                                                                class="caption text-danger"><?php echo e($trade->admin_charge); ?> <?php echo e(optional($trade->receiverCurrency)->code); ?></span>
                                                        </li>
                                                        <li><?php echo app('translator')->get('Processing Time:'); ?> <span
                                                                class="caption"><?php echo e($trade->processing_minutes); ?> <?php echo app('translator')->get('Minutes'); ?></span>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="mb-3"><?php echo app('translator')->get('Instructions to be followed'); ?></h6>
                                <div class="accordion-item">
                                    <h5 class="accordion-header" id="headingTwo">
                                        <button
                                            class="accordion-button collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo"
                                            aria-expanded="false"
                                            aria-controls="collapseTwo">
                                            <?php echo app('translator')->get('terms of trade'); ?>
                                        </button>
                                    </h5>
                                    <div
                                        id="collapseTwo"
                                        class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <?php echo e(optional($trade->advertise)->terms_of_trade); ?>

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
                                            <?php echo app('translator')->get('payment details'); ?>
                                        </button>
                                    </h5>
                                    <div
                                        id="collapseThree"
                                        class="accordion-collapse collapse"
                                        aria-labelledby="headingThree"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <?php echo e(optional($trade->advertise)->payment_details); ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('loadModal'); ?>
    <!-- Dispute Modal -->
    <div class="modal fade" id="disputeModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel"><?php echo app('translator')->get('Dispute Confirmation'); ?></h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('user.trade.dispute')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="tradeId" value="<?php echo e($trade->id); ?>">
                    <div class="modal-body">
                        <div class="row g-4">
                            <p><?php echo app('translator')->get('Are you sure that you have release this trade?'); ?></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                        <button type="submit" class="btn-custom"><?php echo app('translator')->get('Yes'); ?></button>
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
                    <h5 class="modal-title" id="editModalLabel"><?php echo app('translator')->get('Release Confirmation'); ?></h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('user.trade.release')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="tradeId" value="<?php echo e($trade->id); ?>">
                    <div class="modal-body">
                        <div class="row g-4">
                            <p><?php echo app('translator')->get('Are you sure that you have release this trade?'); ?></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                        <button type="submit" class="btn-custom"><?php echo app('translator')->get('Yes'); ?></button>
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
                    <h5 class="modal-title" id="editModalLabel"><?php echo app('translator')->get('Cancel Confirmation'); ?></h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('user.trade.cancel')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="tradeId" value="<?php echo e($trade->id); ?>">
                    <div class="modal-body">
                        <div class="row g-4">
                            <p><?php echo app('translator')->get('Are you sure to cancel this trade?'); ?></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                        <button type="submit" class="btn-custom"><?php echo app('translator')->get('Yes'); ?></button>
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
                    <h5 class="modal-title" id="editModalLabel"><?php echo app('translator')->get('Paid Confirmation'); ?></h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('user.trade.paid')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="tradeId" value="<?php echo e($trade->id); ?>">
                    <div class="modal-body">
                        <div class="row g-4">
                            <p><?php echo app('translator')->get('Are you sure that you have paid the amount?'); ?></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                        <button type="submit" class="btn-custom"><?php echo app('translator')->get('Yes'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
          const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        'use strict';
        let pushChatArea = new Vue({
            el: "#pushChatArea",
            data: {
                items: [],
                auth_id: "<?php echo e(auth()->id()); ?>",
                auth_model: "App\\Models\\User",
                logo: "<?php echo e(asset(config('location.logoIcon.path').'logo.png')); ?>",
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
                    axios.get("<?php echo e(route('user.push.chat.show',$trade->hash_slug)); ?>")
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
                        Notiflix.Notify.Failure(`<?php echo e(trans('Type your message')); ?>`);
                        return 0;
                    }

                    axios.post("<?php echo e(route('user.push.chat.newMessage')); ?>", {
                        trade_id: "<?php echo e($trade->id); ?>",
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
    <?php if($errors->any()): ?>
        <script>
            'use strict';
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            Notiflix.Notify.Failure(`<?php echo e(trans($error)); ?>`);
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/trade/details.blade.php ENDPATH**/ ?>