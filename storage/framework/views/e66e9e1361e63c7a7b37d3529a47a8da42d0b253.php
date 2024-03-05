<?php $__env->startSection('title', trans('Buy')); ?>
<?php $__env->startSection('content'); ?>
    <!-- buy sell -->
    <section class="buy-sell">
        <div class="container">
            <div class="search-bar">
                <form action="<?php echo e(route('buy')); ?>" method="get">
                    <div class="row g-3 align-items-end">
                        <div class="input-box col-lg-2">
                            <label for=""><?php echo app('translator')->get('Username or Email'); ?></label>
                            <div class="form-group">
                                <input type="text" name="seller"
                                       value="<?php echo e(@request()->seller); ?>"
                                       class="form-control" placeholder="<?php echo app('translator')->get('Username or Email'); ?>">
                            </div>
                        </div>
                        <div class="input-box col-lg-2">
                            <label for=""><?php echo app('translator')->get('Crypto Currency'); ?></label>
                            <select class="form-select" aria-label="Default select example" name="crypto">
                                <option value=""><?php echo app('translator')->get('Select Crypto'); ?></option>
                                <?php $__empty_1 = true; $__currentLoopData = $cryptos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crypto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option
                                        value="<?php echo e($crypto->id); ?>" <?php echo e(@request()->crypto == $crypto->id?'selected':''); ?>>
                                        <?php echo e($crypto->code); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="input-box col-lg-2">
                            <label for=""><?php echo app('translator')->get('Fiat Currency'); ?></label>
                            <select class="form-select" aria-label="Default select example" name="fiat">
                                <option value=""><?php echo app('translator')->get('Select Fiat'); ?></option>
                                <?php $__empty_1 = true; $__currentLoopData = $fiats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fiat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option
                                        value="<?php echo e($fiat->id); ?>" <?php echo e(@request()->fiat == $fiat->id?'selected':''); ?>><?php echo e($fiat->code); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="input-box col-lg-2">
                            <label for=""><?php echo app('translator')->get('Payment Method'); ?></label>
                            <select class="form-select" aria-label="Default select example" name="gateway">
                                <option value=""><?php echo app('translator')->get('Select Method'); ?></option>
                                <?php $__empty_1 = true; $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option
                                        value="<?php echo e($gateway->id); ?>" <?php echo e(@request()->gateway == $gateway->id?'selected':''); ?>><?php echo e($gateway->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="input-box col-lg-2">
                            <label for=""><?php echo app('translator')->get('Offer Location'); ?></label>
                            <select class="form-select" aria-label="Default select example" name="location">
                                <option value=""><?php echo app('translator')->get('Select Location'); ?></option>
                                <?php $__empty_1 = true; $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option
                                        value="<?php echo e($location->address); ?>" <?php echo e(@request()->location == $location->address?'selected':''); ?>><?php echo e(ucfirst($location->address)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="input-box col-lg-2">
                            <div class="form-group">
                                <button type="submit" class="btn-custom w-100">
                                    <i class="fas fa-search"></i> <?php echo app('translator')->get('Search'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-parent table-responsive">
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
                                    <td data-label="<?php echo app('translator')->get('Payment Method'); ?>">
                                        <div class="d-flex flex-wrap">
                                            <?php $__currentLoopData = $item->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('buy',$gateway->id)); ?>"><span class="gateway-color"
                                                                                               style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span></a>
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
                    <?php echo e($buy->appends($_GET)->links($theme.'partials.pagination')); ?>

                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/buy.blade.php ENDPATH**/ ?>