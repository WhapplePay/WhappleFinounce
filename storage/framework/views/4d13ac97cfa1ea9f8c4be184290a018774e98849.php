<?php $__env->startSection('title',trans('Dashboard')); ?>
<?php $__env->startSection('content'); ?>
    <!-- main -->
    <div class="container-fluid">
        <div class="main row" id="firebase-app">
            <div v-if="user_foreground == '1' || user_background == '1'">
                <div class="col-12" v-if="notificationPermission == 'default' && !is_notification_skipped" v-cloak>
                    <div class="alert d-flex justify-content-between align-items-start" role="alert">
                        <div>
                            <i class="fal fa-info-circle"></i> <?php echo app('translator')->get('Do not miss any single important notification! Allow your
                        browser to get instant push notification'); ?>

                            <button class="btn-custom mx-3" id="allow-notification"><?php echo app('translator')->get('Allow me'); ?></button>
                        </div>
                        <button class="close-btn pt-1" @click.prevent="skipNotification"><i class="fal fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12" v-if="notificationPermission == 'denied' && !is_notification_skipped" v-cloak>
                    <div class="alert d-flex justify-content-between align-items-start" role="alert">
                        <div>
                            <i class="fal fa-info-circle"></i> <?php echo app('translator')->get('Please allow your browser to get instant push notification.
                        Allow it from
                        notification setting.'); ?>
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
                            <h5><?php echo app('translator')->get('Advertisement'); ?></h5>
                            <h3><?php echo e($advertise['totalAdvertise']); ?></h3>
                            <i class="fal fa-ad"></i>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-box box-4">
                            <h5><?php echo app('translator')->get('Total Trades'); ?></h5>
                            <h3><?php echo e($trades['totalTrade']); ?></h3>
                            <i class="fal fa-file-spreadsheet"></i>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-box box-2">
                            <h5><?php echo app('translator')->get('Running Trades'); ?></h5>
                            <h3><?php echo e($trades['runningTrade']); ?></h3>
                            <i class="fal fa-spinner"></i>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-box box-3">
                            <h5><?php echo app('translator')->get('Complete Trades'); ?></h5>
                            <h3><?php echo e($trades['completeTrade']); ?></h3>
                            <i class="fal fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <!-- table -->
                <div class="table-parent table-responsive">
                    <h6 class="my-3 text-white"><?php echo app('translator')->get('Last 10 Trade Lists'); ?></h6>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><?php echo app('translator')->get('Trade Number'); ?></th>
                            <th><?php echo app('translator')->get('With'); ?></th>
                            <th><?php echo app('translator')->get('Type'); ?></th>
                            <th><?php echo app('translator')->get('Currency'); ?></th>
                            <th><?php echo app('translator')->get('Payment Method'); ?></th>
                            <th><?php echo app('translator')->get('Rate'); ?></th>
                            <th><?php echo app('translator')->get('Crypto Amount'); ?></th>
                            <th><?php echo app('translator')->get('Status'); ?></th>
                            <th><?php echo app('translator')->get('Requested On'); ?></th>
                            <th><?php echo app('translator')->get('Action'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentTrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td data-label="<?php echo app('translator')->get('SL No.'); ?>"><?php echo e($item->trade_number); ?></td>
                                <td data-label="<?php echo app('translator')->get('With'); ?>">
                                    <a href="javascript:void(0)">
                                        <div class="d-lg-flex d-block align-items-center">
                                            <?php if($item->owner_id != auth()->user()->id): ?>
                                                <div class="me-3"><img
                                                        src="<?php echo e(getFile(config('location.user.path').optional($item->owner)->image)); ?>"
                                                        alt="user" class="rounded-circle" width="45"
                                                        height="45">
                                                    <i class="tb-online position-absolute fa fa-circle text-<?php echo e((optional($item->owner)->lastSeen == true) ?trans('success'):trans('danger')); ?>"
                                                       title="<?php echo e((optional($item->owner)->lastSeen == true) ?trans('Online'):trans('Away')); ?>"></i>
                                                </div>
                                                <div class="">
                                                    <h6 class="text-white mb-0 text-lowercase"><?php echo app('translator')->get(optional($item->owner)->username); ?></h6>
                                                    <span
                                                        class="text-muted font-10"><?php echo e(optional($item->owner)->total_trade); ?> <?php echo app('translator')->get('Trades'); ?> |</span>
                                                    <?php if(optional($item->owner)->total_trade>0): ?>
                                                        <span
                                                            class="text-muted font-10"><?php echo app('translator')->get('Completion'); ?> <?php echo e(number_format(optional($item->owner)->completed_trade*100/optional($item->owner)->total_trade,2)); ?>

                                                            %</span>
                                                    <?php else: ?>
                                                        <span
                                                            class="text-muted font-10"><?php echo app('translator')->get('Completion'); ?> 0
                                                            %</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="me-3"><img
                                                        src="<?php echo e(getFile(config('location.user.path').optional($item->sender)->image)); ?>"
                                                        alt="user" class="rounded-circle" width="45"
                                                        height="45">
                                                    <i class="tb-online position-absolute fa fa-circle text-<?php echo e((optional($item->sender)->lastSeen == true) ?trans('success'):trans('danger')); ?>"
                                                       title="<?php echo e((optional($item->sender)->lastSeen == true) ?trans('Online'):trans('Away')); ?>"></i>
                                                </div>
                                                <div class="">
                                                    <h6 class="text-white mb-0 text-lowercase"><?php echo app('translator')->get(optional($item->sender)->username); ?></h6>
                                                    <span
                                                        class="text-muted font-10"><?php echo e(optional($item->sender)->total_trade); ?> <?php echo app('translator')->get('Trades'); ?> |</span>
                                                    <?php if(optional($item->sender)->total_trade>0): ?>
                                                        <span
                                                            class="text-muted font-10"><?php echo app('translator')->get('Completion'); ?> <?php echo e(number_format(optional($item->sender)->completed_trade*100/optional($item->sender)->total_trade,2)); ?>

                                                            %</span>
                                                    <?php else: ?>
                                                        <span
                                                            class="text-muted font-10"><?php echo app('translator')->get('Completion'); ?> 0
                                                            %</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                </td>
                                <?php if($item->owner_id == auth()->user()->id): ?>
                                    <td data-label="<?php echo app('translator')->get('Type'); ?>"><span
                                            class="badge <?php echo e(($item->type == 'buy')? 'bg-success' : 'bg-warning'); ?>"><?php echo app('translator')->get($item->type); ?></span>
                                    </td>
                                <?php else: ?>
                                    <td data-label="<?php echo app('translator')->get('Type'); ?>"><span
                                            class="badge <?php echo e(($item->type == 'buy')? 'bg-warning' : 'bg-success'); ?>"><?php echo e($item->type == 'buy'? 'sell':'buy'); ?></span>
                                    </td>
                                <?php endif; ?>
                                <td data-label="<?php echo app('translator')->get('Currency'); ?>"><?php echo app('translator')->get(optional($item->currency)->code); ?></td>
                                <td data-label="<?php echo app('translator')->get('Payment Method'); ?>"><?php $__currentLoopData = $item->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="gateway-color"
                                              style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                                <td data-label="<?php echo app('translator')->get('Rate'); ?>"><?php echo e(getAmount($item->rate+0,2)); ?> <?php echo e(optional($item->currency)->code); ?>

                                    /<?php echo e(optional($item->receiverCurrency)->code); ?></td>
                                <td data-label="<?php echo app('translator')->get('Crypto Amount'); ?>"><?php echo e(getAmount($item->receive_amount+0,8)); ?> <?php echo e(optional($item->receiverCurrency)->code); ?></td>
                                <td data-label="<?php echo app('translator')->get('Status'); ?>">
                                    <?php if($item->status == 0): ?>
                                        <span class="badge bg-warning"><?php echo app('translator')->get('Pending'); ?></span>
                                    <?php elseif($item->status == 1): ?>
                                        <span class="badge bg-success"><?php echo app('translator')->get('Buyer Paid'); ?></span>
                                    <?php elseif($item->status == 3): ?>
                                        <span class="badge bg-danger"><?php echo app('translator')->get('Canceled'); ?></span>
                                    <?php elseif($item->status == 4): ?>
                                        <span class="badge bg-success"><?php echo app('translator')->get('Completed'); ?></span>
                                    <?php elseif($item->status == 5): ?>
                                        <span class="badge bg-danger"><?php echo app('translator')->get('Reported'); ?></span>
                                    <?php elseif($item->status == 6 || $item->status == 7): ?>
                                        <span class="badge bg-primary"><?php echo app('translator')->get('Escrow Refunded'); ?></span>
                                    <?php elseif($item->status == 8): ?>
                                        <span class="badge bg-primary"><?php echo app('translator')->get('Resolved'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="<?php echo app('translator')->get('Requested On'); ?>"><?php echo e(diffForHumans($item->created_at)); ?></td>
                                <td data-label="<?php echo app('translator')->get('Action'); ?>" class="action">
                                    <a href="<?php echo e(route('user.buyCurrencies.tradeDetails',$item->hash_slug)); ?>">
                                        <button class="btn-custom">
                                        <?php echo app('translator')->get('Details'); ?></a>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="100%">
                                    <div class="no-data-message">
                                <span class="icon-wrapper">
                                    <span class="file-icon">
                                        <i class="fas fa-file-times"></i>
                                    </span>
                                </span>
                                        <p class="message"><?php echo app('translator')->get('No data found'); ?></p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php if($firebaseNotify): ?>
    <?php $__env->startPush('script'); ?>
        <script type="module">

            import {initializeApp} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
            import {
                getMessaging,
                getToken,
                onMessage
            } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-messaging.js";

            const firebaseConfig = {
                apiKey: "<?php echo e($firebaseNotify->api_key); ?>",
                authDomain: "<?php echo e($firebaseNotify->auth_domain); ?>",
                projectId: "<?php echo e($firebaseNotify->project_id); ?>",
                storageBucket: "<?php echo e($firebaseNotify->storage_bucket); ?>",
                messagingSenderId: "<?php echo e($firebaseNotify->messaging_sender_id); ?>",
                appId: "<?php echo e($firebaseNotify->app_id); ?>",
                measurementId: "<?php echo e($firebaseNotify->measurement_id); ?>"
            };

            const app = initializeApp(firebaseConfig);
            const messaging = getMessaging(app);
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('<?php echo e(getProjectDirectory()); ?>' + `/firebase-messaging-sw.js`, {scope: './'}).then(function (registration) {
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
                                    vapidKey: "<?php echo e($firebaseNotify->vapid_key); ?>"
                                })
                                    .then((token) => {
                                        $.ajax({
                                            url: "<?php echo e(route('user.save.token')); ?>",
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
                    this.user_foreground = "<?php echo e($firebaseNotify->user_foreground); ?>";
                    this.user_background = "<?php echo e($firebaseNotify->user_background); ?>";
                },
                methods: {
                    skipNotification() {
                        sessionStorage.setItem('is_notification_skipped', '1');
                        this.is_notification_skipped = true;
                    }
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/dashboard.blade.php ENDPATH**/ ?>