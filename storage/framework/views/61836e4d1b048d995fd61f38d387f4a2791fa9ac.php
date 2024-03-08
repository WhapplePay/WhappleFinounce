<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Dashboard'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row " id="firebase-app" v-if="admin_foreground == '1' || admin_background == '1'">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-4 mt-0"
                 v-if="notificationPermission == 'default' && !is_notification_skipped" v-cloak>
                <div class="d-flex justify-content-between align-items-start bd-callout bd-callout-warning  shadow">
                    <div>
                        <i class="fas fa-info-circle mr-2"></i> <?php echo app('translator')->get('Do not miss any single important notification! Allow your
                        browser to get instant push notification'); ?>
                        <button id="allow-notification" class="btn btn-sm btn-primary mx-2"><i
                                class="fa fa-check-circle"></i> <?php echo app('translator')->get('Allow me'); ?></button>
                    </div>
                    <a href="javascript:void(0)" @click.prevent="skipNotification"><i class="fas fa-times"></i></a>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-4 mt-0"
                 v-if="notificationPermission == 'denied' && !is_notification_skipped" v-cloak>
                <div class="d-flex justify-content-between align-items-start bd-callout bd-callout-warning  shadow">
                    <div>
                        <i class="fas fa-info-circle mr-2"></i> <?php echo app('translator')->get('Please allow your browser to get instant push notification.
                        Allow it from
                        notification setting.'); ?>
                    </div>
                    <a href="javascript:void(0)" @click.prevent="skipNotification"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>

        <div class="row admin-fa_icon">

            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e(number_format($userRecord['totalUser'])); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Total Users'); ?>
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e(number_format($userRecord['activeUser'])); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Total Active Users'); ?>
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e(number_format($userRecord['inActiveUser'])); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Total Inactive Users'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($userRecord['todayJoin']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Today Join User'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i data-feather="users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row admin-fa_icon">
            <div class="col-md-12">
                <h4 class="card-title"><?php echo app('translator')->get('Advertisements Statistics'); ?></h4>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($adRecord['totalAd']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Total Advertisements'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fab fa-adversal"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($adRecord['activeAd']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get("Active Advertisements"); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fas fa-2x fa-check-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($adRecord['inActiveAd']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Inactive Advertisements'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fas fa-2x fa-times-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($paymentWindows); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Total Payment Windows'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fas fa-stopwatch"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row admin-fa_icon">
            <div class="col-md-12">
                <h4 class="card-title"><?php echo app('translator')->get('Trades Statistics'); ?></h4>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($userTrades['totalTrade']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Total Trades'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fas fa-chart-line"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($userTrades['runningTrade']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get("Running Trades"); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fas fa-2x fa-spinner"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($userTrades['completeTrade']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Complete Trades'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fas fa-2x fa-check-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($userTrades['reportedTrade']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Reported Trades'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fas fa-exclamation-triangle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row admin-fa_icon">
            <div class="col-md-12">
                <h4 class="card-title"><?php echo app('translator')->get('Currency Statistics'); ?></h4>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($userCurrencies['totalCrypto']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Total Crypto'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fab fa-bitcoin"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($userCurrencies['activeCrypto']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get("Active Crypto"); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fas fa-2x fa-check-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($userCurrencies['totalFiat']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Total Fiat'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fas fa-ruble-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($userCurrencies['activeFiat']); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Active Fiat'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fas fa-2x fa-check-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="card-title"><?php echo app('translator')->get("This Month's Summary"); ?></h4>
                                <div>
                                    <canvas id="line-chart" height="150"></canvas>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <h4 class="card-title"><?php echo app('translator')->get('Crypto Currencies Uses'); ?></h4>
                                <div>
                                    <canvas id="pie-chart" height="280"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row admin-fa_icon">
            <div class="col-md-12">
                <h4 class="card-title"><?php echo app('translator')->get('Tickets'); ?></h4>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e(number_format($tickets['closed'])); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Closed Ticket'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fa fa-2x fa-times-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e(number_format($tickets['replied'])); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Replied Ticket'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fa fa-2x fa-inbox"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e(number_format($tickets['answered'])); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Answered Ticket'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fa fa-2x fa-check"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo e(number_format($tickets['pending'])); ?></h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo app('translator')->get('Pending Ticket'); ?></h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fa fa-2x fa-spinner"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($basic->is_active_cron_notification): ?>
        <div class="modal fade" id="cron-info" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header modal-colored-header bg-primary">
                        <h5 class="modal-title">
                            <i class="fas fa-info-circle"></i>
                            <?php echo app('translator')->get('Cron Job Set Up Instruction'); ?>
                        </h5>
                        <button type="button" class="close cron-notification-close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="bg-orange text-white p-2">
                                    <i><?php echo app('translator')->get('**To sending emails and sms and updating Investment return automatically you need to setup cron job in your server. Make sure your job is running properly. We insist to set the cron job time as minimum as possible.**'); ?></i>
                                </p>
                            </div>
                            <div class="col-md-12 form-group">
                                <label><strong><?php echo app('translator')->get('Command for Email & SMS'); ?></strong></label>
                                <div class="input-group ">
                                    <input type="text" class="form-control copyText"
                                           value="curl -s <?php echo e(route('queue.work')); ?>" disabled>
                                    <div class="input-group-append">
                                        <button class="input-group-text bg-primary btn btn-primary text-white copy-btn">
                                            <i class="fas fa-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <label><strong><?php echo app('translator')->get('Command For Gate Update Rate'); ?></strong></label>
                                <div class="input-group ">
                                    <input type="text" class="form-control copyText"
                                           value="curl -s <?php echo e(route('cron')); ?>"
                                           disabled>
                                    <div class="input-group-append">
                                        <button class="input-group-text bg-primary btn btn-primary text-white copy-btn">
                                            <i class="fas fa-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <p class="bg-dark text-white p-2">
                                    <?php echo app('translator')->get('*To turn off this pop up go to '); ?>
                                    <a href="<?php echo e(route('admin.basic-controls')); ?>"
                                       class="text-orange"><?php echo app('translator')->get('Basic control'); ?></a>
                                    <?php echo app('translator')->get(' and disable `Cron Set Up Pop Up`.*'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('assets/admin/js/Chart.min.js')); ?>"></script>

    <script>
        "use strict";
        new Chart(document.getElementById("line-chart"), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($statistics['schedule']->keys(), 15, 512) ?>,
                datasets: [{
                    data: <?php echo json_encode($statistics['ad']->values(), 15, 512) ?>,
                    label: "Advertisements",
                    borderColor: "#6fbbff",
                    fill: false
                }, {
                    data: <?php echo json_encode($statistics['trade']->values(), 15, 512) ?>,
                    label: "Trades",
                    borderColor: "#ff6f62",
                    fill: false
                }
                ]
            }
        });


        new Chart(document.getElementById("pie-chart"), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($pieLog->pluck('level'), 15, 512) ?>,
                datasets: [{
                    backgroundColor: ["#6fbbff", "#ff6f62", "#05ffe4", "#98df8a", "#8b6ef3", "#f9dd7e", "#f34da3"],
                    data:  <?php echo json_encode($pieLog->pluck('value'), 15, 512) ?>,
                }]
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function (tooltipItems, data) {
                            return data.labels[tooltipItems.index] + ': ' + data.datasets[0].data[tooltipItems.index] + '%';
                        }
                    }

                }
            }
        });


        $(document).on('click', '#details', function () {
            var title = $(this).data('servicetitle');
            var description = $(this).data('description');
            $('#title').text(title);
            $('#servicedescription').text(description);
        });

        $(document).ready(function () {
            let isActiveCronNotification = '<?php echo e($basic->is_active_cron_notification); ?>';
            if (isActiveCronNotification == 1)
                $('#cron-info').modal('show');
            $(document).on('click', '.copy-btn', function () {
                var _this = $(this)[0];
                var copyText = $(this).parents('.input-group-append').siblings('input');
                $(copyText).prop('disabled', false);
                copyText.select();
                document.execCommand("copy");
                $(copyText).prop('disabled', true);
                $(this).text('Coppied');
                setTimeout(function () {
                    $(_this).text('');
                    $(_this).html('<i class="fas fa-copy"></i>');
                }, 500)
            });
        })
    </script>
<?php $__env->stopPush(); ?>
<?php if($firebaseNotify): ?>
    <?php $__env->startPush('js'); ?>
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
                                            url: "<?php echo e(route('admin.save.token')); ?>",
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
                    admin_foreground: '',
                    admin_background: '',
                    notificationPermission: Notification.permission,
                    is_notification_skipped: sessionStorage.getItem('is_notification_skipped') == '1'
                },
                mounted() {
                    this.admin_foreground = "<?php echo e($firebaseNotify->admin_foreground); ?>";
                    this.admin_background = "<?php echo e($firebaseNotify->admin_background); ?>";
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>