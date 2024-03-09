<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Profile'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- user profile -->
    <div class="container-fluid">
        <div class="main row">
            <section class="user-profile">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="sidebar-wrapper">
                            <div class="profile">
                                <div class="img">
                                    <img id="profile"
                                         src="<?php echo e(asset(getFile(config('location.user.path').$user->image))); ?>" alt="..."
                                         class="img-fluid"/>
                                    <i class="profile-online position-absolute fa fa-circle text-<?php echo e(($user->lastSeen == true) ?trans('success'):trans('warning')); ?>"
                                       title="<?php echo e(($user->lastSeen == true) ?trans('Online'):trans('Away')); ?>"></i>
                                </div>
                                <div>
                                    <h5 class="name">
                                        <?php echo e($user->fullname); ?>

                                        <?php if($user->identity_verify == 2): ?>
                                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                                        <?php endif; ?>
                                    </h5>
                                    <span class="username"><?php echo e($user->username); ?></span>
                                </div>
                            </div>
                            <ul>
                                <li><?php echo app('translator')->get('Country:'); ?> <span><?php echo e(ucfirst($user->address)); ?></span></li>
                                <li><?php echo app('translator')->get('Joined:'); ?>
                                    <span><?php echo e(\Carbon\Carbon::parse($user->created_at)->diffForHumans()); ?></span></li>
                                <?php if($user->email_verification == 1): ?>
                                    <li><?php echo app('translator')->get('Email:'); ?> <span class="status-success"><?php echo app('translator')->get('verified'); ?></span></li>
                                <?php else: ?>
                                    <li><?php echo app('translator')->get('Email:'); ?> <span class="status-danger"><?php echo app('translator')->get('Unverified'); ?></span></li>
                                <?php endif; ?>

                                <?php if($user->sms_verification == 1): ?>
                                    <li><?php echo app('translator')->get('Phone:'); ?> <span class="status-success"><?php echo app('translator')->get('verified'); ?></span></li>
                                <?php else: ?>
                                    <li><?php echo app('translator')->get('Phone:'); ?> <span class="status-danger"><?php echo app('translator')->get('Unverified'); ?></span></li>
                                <?php endif; ?>
                                <li><?php echo app('translator')->get('Complete Trades:'); ?> <span
                                        class="badge bg-success"><?php echo e($user->completed_trade); ?></span></li>
                                <li><?php echo app('translator')->get('Advertisements:'); ?> <span
                                        class="badge bg-dark"><?php echo e(count($user->advertise)); ?></span></li>
                                <li><?php echo app('translator')->get('Avg Speed:'); ?>
                                    <?php if($user->completed_trade > 0): ?>
                                        <span><?php echo e(number_format($user->total_min/$user->completed_trade,0)); ?> <?php echo app('translator')->get('Minutes'); ?></span>
                                    <?php else: ?>
                                        <span>0 <?php echo app('translator')->get('Minutes'); ?></span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div>
                            <h5><?php echo app('translator')->get('Latest 10 Buy Ads'); ?></h5>
                            <div class="table-parent table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('Payment Method'); ?></th>
                                        <th><?php echo app('translator')->get('Rate'); ?></th>
                                        <th><?php echo app('translator')->get('Limit'); ?></th>
                                        <th><?php echo app('translator')->get('Payment Window'); ?></th>
                                        <th><?php echo app('translator')->get('Action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $user->advertise->where('type','sell')->sortBy('desc')->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td data-label="<?php echo app('translator')->get('Payment Method'); ?>">
                                                <div class="d-flex flex-wrap">
                                                    <?php $__currentLoopData = $item->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="gateway-color"
                                                          style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </td>
                                            <td data-label="<?php echo app('translator')->get('Rate'); ?>"><?php echo app('translator')->get(number_format($item->rate,3)); ?> <?php echo e(optional($item->fiatCurrency)->code); ?>

                                                /<?php echo e(optional($item->cryptoCurrency)->code); ?></td>
                                            <td data-label="<?php echo app('translator')->get('Limit'); ?>"><?php echo e($item->minimum_limit); ?>

                                                - <?php echo e($item->maximum_limit); ?> <?php echo e(optional($item->fiatCurrency)->code); ?></td>
                                            <td data-label="<?php echo app('translator')->get('Payment Window'); ?>"><?php echo e(optional($item->paymentWindow)->name); ?></td>
                                            <td data-label="<?php echo app('translator')->get('Action'); ?>" class="action">
                                                <a href="<?php echo e(route('user.buyCurrencies.tradeRqst',$item->id)); ?>"
                                                   class="btn-custom p-6">
                                                    <?php echo app('translator')->get('Buy'); ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="100%">
                                                <div class="no-data-message"><span class="icon-wrapper">
                                                 <span class="file-icon"><i class="fas fa-file-times"></i></span></span>
                                                    <p class="message"><?php echo app('translator')->get('No data found'); ?></p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-5">
                            <h5><?php echo app('translator')->get('Latest 10 Sell Ads'); ?></h5>
                            <div class="table-parent table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('Payment Method'); ?></th>
                                        <th><?php echo app('translator')->get('Rate'); ?></th>
                                        <th><?php echo app('translator')->get('Limit'); ?></th>
                                        <th><?php echo app('translator')->get('Payment Window'); ?></th>
                                        <th><?php echo app('translator')->get('Action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $user->advertise->where('type','buy')->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td data-label="<?php echo app('translator')->get('Payment Method'); ?>">
                                                <div class="d-flex flex-wrap">
                                                    <?php $__currentLoopData = $item->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="gateway-color"
                                                          style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </td>
                                            <td data-label="<?php echo app('translator')->get('Rate'); ?>"><?php echo app('translator')->get(number_format($item->rate,3)); ?> <?php echo e(optional($item->fiatCurrency)->code); ?>

                                                /<?php echo e(optional($item->cryptoCurrency)->code); ?></td>
                                            <td data-label="<?php echo app('translator')->get('Limit'); ?>"><?php echo e($item->minimum_limit); ?>

                                                - <?php echo e($item->maximum_limit); ?> <?php echo e(optional($item->fiatCurrency)->code); ?></td>
                                            <td data-label="<?php echo app('translator')->get('Payment Window'); ?>"><?php echo e(optional($item->paymentWindow)->name); ?></td>
                                            <td data-label="<?php echo app('translator')->get('Action'); ?>" class="action">
                                                <a href="<?php echo e(route('user.sellCurrencies.tradeRqst',$item->id)); ?>"
                                                   class="btn-custom p-6">
                                                    <?php echo app('translator')->get('Sell'); ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="100%">
                                                <div class="no-data-message"><span class="icon-wrapper">
                                                 <span class="file-icon"><i class="fas fa-file-times"></i></span></span>
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
            </section>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/public-profile/page.blade.php ENDPATH**/ ?>