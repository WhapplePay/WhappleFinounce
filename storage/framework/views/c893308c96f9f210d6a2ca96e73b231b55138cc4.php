<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get("Trades List"); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="page-header card card-primary m-0 m-md-4 my-4 m-md-0 p-5 shadow">
        <div class="row justify-content-between">
            <div class="col-md-12">
                <form action="<?php echo e(route('admin.trade.list')); ?>" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="tradeNumber" value="<?php echo e(@request()->tradeNumber); ?>"
                                       class="form-control get-trx-id"
                                       placeholder="<?php echo app('translator')->get('Search for Trade Number'); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="owner" value="<?php echo e(@request()->owner); ?>"
                                       class="form-control get-username"
                                       placeholder="<?php echo app('translator')->get('Search for Owner username or email'); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="sender" value="<?php echo e(@request()->sender); ?>"
                                       class="form-control get-username"
                                       placeholder="<?php echo app('translator')->get('Search for Requester username or email'); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="submit" class="btn waves-effect waves-light btn-primary w-100"><i
                                        class="fas fa-search"></i> <?php echo app('translator')->get('Search'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <table class="categories-show-table table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th scope="col"><?php echo app('translator')->get('Trade Number'); ?></th>
                    <th scope="col"><?php echo app('translator')->get('Owner'); ?></th>
                    <th scope="col"><?php echo app('translator')->get('Requester'); ?></th>
                    <th scope="col"><?php echo app('translator')->get('Amount'); ?></th>
                    <th scope="col"><?php echo app('translator')->get('Payment Method'); ?></th>
                    <th scope="col"><?php echo app('translator')->get('Exchange Rate'); ?></th>
                    <th scope="col"><?php echo app('translator')->get('Crypto Amount'); ?></th>
                    <th scope="col"><?php echo app('translator')->get('Status'); ?></th>
                    <th scope="col"><?php echo app('translator')->get('Action'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $trades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="<?php echo app('translator')->get('Trade Number'); ?>"><?php echo e($item->trade_number); ?></td>
                        <td data-label="<?php echo app('translator')->get('Owner'); ?>">
                            <a href="<?php echo e(route('admin.user-edit',$item->owner_id )); ?>">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img
                                            src="<?php echo e(getFile(config('location.user.path') . optional($item->owner)->image)); ?>"
                                            alt="user" class="rounded-circle" width="35" height="35"></div>
                                    <div class="mr-3">
                                        <h5 class="text-dark mb-0 font-16 font-weight-medium"><?php echo app('translator')->get(optional($item->owner)->username); ?></h5>
                                        <span
                                            class="text-muted font-10"><?php echo e(optional($item->owner)->total_trade); ?> <?php echo app('translator')->get('Trades'); ?> |</span>
                                        <?php if(optional($item->owner)->total_trade > 0): ?>
                                            <span
                                                class="text-muted font-10"><?php echo app('translator')->get('Completion'); ?> <?php echo e(number_format(optional($item->owner)->completed_trade*100/optional($item->owner)->total_trade,2)); ?>

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
                        <td data-label="<?php echo app('translator')->get('Requester'); ?>">
                            <a href="<?php echo e(route('admin.user-edit',$item->sender_id )); ?>">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img
                                            src="<?php echo e(getFile(config('location.user.path') . optional($item->sender)->image)); ?>"
                                            alt="user" class="rounded-circle" width="35" height="35"></div>
                                    <div class="mr-3">
                                        <h5 class="text-dark mb-0 font-16 font-weight-medium"><?php echo app('translator')->get(optional($item->sender)->username); ?></h5>
                                        <span
                                            class="text-muted font-10"><?php echo e(optional($item->sender)->total_trade); ?> <?php echo app('translator')->get('Trades'); ?> |</span>
                                        <?php if(optional($item->sender)->total_trade > 0): ?>
                                            <span
                                                class="text-muted font-10"><?php echo app('translator')->get('Completion'); ?> <?php echo e(number_format(optional($item->sender)->completed_trade*100/optional($item->sender)->total_trade,2)); ?>

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
                        <td data-label="<?php echo app('translator')->get('Amount'); ?>"><?php echo e($item->pay_amount); ?> <?php echo e(optional($item->currency)->code); ?></span>
                        </td>
                        <td data-label="<?php echo app('translator')->get('Payment Method'); ?>"><?php $__currentLoopData = $item->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="gateway-color"
                                      style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td>
                        <td data-label="<?php echo app('translator')->get('Exchange Rate'); ?>"><?php echo e(getAmount($item->rate+0,2)); ?> <?php echo e(optional($item->currency)->code); ?>

                            /<?php echo e(optional($item->receiverCurrency)->code); ?></td>
                        <td data-label="<?php echo app('translator')->get('Crypto Amount'); ?>"><?php echo e(getAmount($item->receive_amount,8)); ?> <?php echo e(optional($item->receiverCurrency)->code); ?></span>
                        </td>
                        <td data-label="<?php echo app('translator')->get('Status'); ?>">
                            <?php if($item->status == 0): ?>
                                <span class="badge badge-warning"><?php echo app('translator')->get('Pending'); ?></span>
                            <?php elseif($item->status == 1): ?>
                                <span class="badge badge-success"><?php echo app('translator')->get('Buyer Paid'); ?></span>
                            <?php elseif($item->status == 3): ?>
                                <span class="badge badge-danger"><?php echo app('translator')->get('Canceled'); ?></span>
                            <?php elseif($item->status == 4): ?>
                                <span class="badge badge-success"><?php echo app('translator')->get('Completed'); ?></span>
                            <?php elseif($item->status == 5): ?>
                                <span class="badge badge-danger"><?php echo app('translator')->get('Reported'); ?></span>
                            <?php elseif($item->status == 6 || $item->status == 7): ?>
                                <span class="badge badge-info"><?php echo app('translator')->get('Escrow Funded'); ?></span>
                                   <?php elseif($item->status == 8): ?>
                                <span class="badge badge-primary"><?php echo app('translator')->get('Resolved'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td data-label="<?php echo app('translator')->get('Action'); ?>">
                            <a href="<?php echo e(route('admin.trade.Details',$item->hash_slug)); ?>"
                               class="btn btn-sm btn-outline-info"
                               data-toggle="tooltip" title="" data-original-title="Details">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td class="text-center text-danger" colspan="8"><?php echo app('translator')->get('No Trades Data'); ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <?php echo e($trades->links('partials.pagination')); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/trades/list.blade.php ENDPATH**/ ?>