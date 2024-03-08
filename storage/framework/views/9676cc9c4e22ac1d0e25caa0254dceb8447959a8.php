<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Trade'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <div class="search-bar">
                    <form action="<?php echo e(route('user.trade.list')); ?>" method="get">
                        <div class="row g-3 align-items-end">
                            <div class="input-box col-lg-3">
                                <label for=""><?php echo app('translator')->get('Trade Number'); ?></label>
                                <div class="form-group">
                                    <input type="text" name="tradeNumber"
                                           value="<?php echo e(@request()->tradeNumber); ?>"
                                           class="form-control" placeholder="<?php echo app('translator')->get('Trade Number'); ?>">
                                </div>
                            </div>
                            <div class="input-box col-lg-3">
                                <label for=""><?php echo app('translator')->get('Username'); ?></label>
                                <div class="form-group">
                                    <input type="text" name="username"
                                           value="<?php echo e(@request()->username); ?>"
                                           class="form-control" placeholder="<?php echo app('translator')->get('Username'); ?>">
                                </div>
                            </div>
                            <div class="input-box col-lg-3">
                                <label for=""><?php echo app('translator')->get('Status'); ?></label>
                                <select class="form-select" aria-label="Default select example" name="status">
                                    <option selected disabled><?php echo app('translator')->get('Select Status'); ?></option>
                                    <option
                                        value="0" <?php echo e(@request()->status == '0'?'selected':''); ?>><?php echo app('translator')->get('Pending'); ?></option>
                                    <option
                                        value="1" <?php echo e(@request()->status == '1'?'selected':''); ?>><?php echo app('translator')->get('Buyer paid'); ?></option>
                                    <option
                                        value="3" <?php echo e(@request()->status == '3'?'selected':''); ?>><?php echo app('translator')->get('Cancel'); ?></option>
                                    <option
                                        value="4" <?php echo e(@request()->status == '4'?'selected':''); ?>><?php echo app('translator')->get('Complete'); ?></option>
                                    <option
                                        value="5" <?php echo e(@request()->status == '5'?'selected':''); ?>><?php echo app('translator')->get('Reported'); ?></option>
                                </select>
                            </div>

                            <div class="input-box col-lg-3">
                                <div class="form-group">
                                    <button type="submit" class="btn-custom w-100">
                                        <i class="fas fa-search"></i> <?php echo app('translator')->get('Search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="table-parent table-responsive">
            <table class="table table-striped" id="service-table">
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
                <?php $__empty_1 = true; $__currentLoopData = $trades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="<?php echo app('translator')->get('SL No.'); ?>"><?php echo e($item->trade_number); ?></td>
                        <td data-label="<?php echo app('translator')->get('With'); ?>">
                            <?php if($item->owner_id != auth()->user()->id): ?>
                                <a href="<?php echo e(route('user.profile.page',optional($item->owner)->id)); ?>">
                                    <div class="d-lg-flex d-block align-items-center">
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
                                            <span
                                                class="text-muted font-10"><?php echo app('translator')->get('Completion'); ?> <?php echo e(number_format(optional($item->owner)->completed_trade*100/optional($item->owner)->total_trade,2)); ?>%</span>
                                        </div>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('user.profile.page',optional($item->sender)->id)); ?>">
                                    <div class="d-lg-flex d-block align-items-center">
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
                                            <span
                                                class="text-muted font-10"><?php echo app('translator')->get('Completion'); ?> <?php echo e(number_format(optional($item->sender)->completed_trade*100/optional($item->sender)->total_trade,2)); ?>%</span>
                                        </div>
                                    </div>
                                </a>
                            <?php endif; ?>
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
                        <td data-label="<?php echo app('translator')->get('Payment Method'); ?>">
                            <div class="d-flex flex-wrap">
                                <?php $__currentLoopData = $item->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="gateway-color"
                                          style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
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
                            <a href="<?php echo e(route('user.buyCurrencies.tradeDetails',$item->hash_slug)); ?>"
                               class="btn-custom p-6">
                                <?php echo app('translator')->get('Details'); ?></a>
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
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php echo e($trades->appends($_GET)->links($theme.'partials.pagination')); ?>

            </ul>
        </nav>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/trade/list.blade.php ENDPATH**/ ?>