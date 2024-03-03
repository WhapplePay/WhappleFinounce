<!-- buy sell -->
<?php if($buy): ?>
    <section class="buy-sell">
        <div class="container">
            <?php if(isset($templates['buy-sell'][0]) && $buy_sell = $templates['buy-sell'][0]): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5><?php echo app('translator')->get(optional($buy_sell->description)->title); ?></h5>
                            <h2><?php echo app('translator')->get(optional($buy_sell->description)->sub_title); ?></h2>
                            <p class="mx-auto">
                                <?php echo app('translator')->get(optional($buy_sell->description)->short_description); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col">
                    <div
                        class="table-parent table-responsive"
                        data-aos="fade-up"
                        data-aos-duration="1000"
                        data-aos-anchor-placement="center-bottom">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Seller'); ?></th>
                                <th><?php echo app('translator')->get('Payment Method'); ?></th>
                                <th><?php echo app('translator')->get('Rate'); ?></th>
                                <th><?php echo app('translator')->get('Limit'); ?></th>
                                <th><?php echo app('translator')->get('Payment Window'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $buy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td data-label="<?php echo app('translator')->get('Seller'); ?>">
                                        <a href="<?php echo e(route('user.profile.page',$item->user_id)); ?>">
                                            <div class="d-lg-flex d-block align-items-center">
                                                <div class="me-3"><img
                                                        src="<?php echo e(getFile(config('location.user.path').optional($item->user)->image)); ?>"
                                                        alt="user" class="rounded-circle" width="45"
                                                        height="45"></div>
                                                <div class="">
                                                    <h6 class="text-white mb-0 text-lowercase"><?php echo app('translator')->get(optional($item->user)->username); ?></h6>
                                                    <span
                                                        class="text-muted font-10"><?php echo e(optional($item->user)->total_trade); ?> <?php echo app('translator')->get('Trades'); ?> |</span>
                                                    <?php if(optional($item->user)->total_trade > 0): ?>
                                                        <span
                                                            class="text-muted font-10"><?php echo app('translator')->get('Completion'); ?> <?php echo e(number_format(optional($item->user)->completed_trade*100/optional($item->user)->total_trade,2)); ?>

                                                %</span>
                                                    <?php else: ?>
                                                        <span
                                                            class="text-muted font-10"><?php echo app('translator')->get('Completion'); ?> 0
                                                %</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td data-label="<?php echo app('translator')->get('Payment Method'); ?>"><?php $__currentLoopData = $item->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="gateway-color"
                                                  style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                                    <td data-label="<?php echo app('translator')->get('Rate'); ?>"><?php echo app('translator')->get(number_format($item->rate,3)); ?> <?php echo e(optional($item->fiatCurrency)->code); ?>

                                        /<?php echo e(optional($item->cryptoCurrency)->code); ?></td>
                                    <td data-label="<?php echo app('translator')->get('Limit'); ?>"><?php echo e($item->minimum_limit); ?>

                                        - <?php echo e($item->maximum_limit); ?> <?php echo e(optional($item->fiatCurrency)->code); ?></td>
                                    <td data-label="<?php echo app('translator')->get('Payment Window'); ?>"><?php echo e(optional($item->paymentWindow)->name); ?></td>
                                    <td data-label="<?php echo app('translator')->get('Action'); ?>" class="action">
                                        <a href="<?php echo e(route('user.buyCurrencies.tradeRqst',$item->id)); ?>">
                                            <button class="btn-custom">
                                            <?php echo app('translator')->get('Buy'); ?></a>
                                        </button>
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
<?php endif; ?>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/sections/buy-sell.blade.php ENDPATH**/ ?>