<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Advertisements'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <div class="search-bar">
                    <form action="<?php echo e(route('user.advertisements.list')); ?>" method="get">
                        <div class="row g-3 align-items-end">
                            <div class="input-box col-lg-3">
                                <label for=""><?php echo app('translator')->get('Currency Code'); ?></label>
                                <div class="form-group">
                                    <input type="text" name="currencyCode"
                                           value="<?php echo e(@request()->currencyCode); ?>"
                                           class="form-control" placeholder="<?php echo app('translator')->get('Currency Code'); ?>">
                                </div>
                            </div>

                            <div class="input-box col-lg-3">
                                <label for=""><?php echo app('translator')->get('Type'); ?></label>
                                <select class="form-select" aria-label="Default select example" name="type">
                                    <option selected disabled><?php echo app('translator')->get('Select Type'); ?></option>
                                    <option
                                        value="buy" <?php echo e(@request()->type == 'buy'?'selected':''); ?>><?php echo app('translator')->get('Buy'); ?></option>
                                    <option
                                        value="sell" <?php echo e(@request()->type == 'sell'?'selected':''); ?>><?php echo app('translator')->get('Sell'); ?></option>
                                </select>
                            </div>

                            <div class="input-box col-lg-3">
                                <label for=""><?php echo app('translator')->get('Status'); ?></label>
                                <select class="form-select" aria-label="Default select example" name="status">
                                    <option selected disabled><?php echo app('translator')->get('Select Status'); ?></option>
                                    <option
                                        value="1" <?php echo e(@request()->status == '1'?'selected':''); ?>><?php echo app('translator')->get('Enabled'); ?></option>
                                    <option
                                        value="0" <?php echo e(@request()->status == '0'?'selected':''); ?>><?php echo app('translator')->get('Disable'); ?></option>
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
            <div class="text-end mb-4 me-3">
                <a href="<?php echo e(route('user.advertisements.create')); ?>">
                    <button class="btn-modify">
                        <?php echo app('translator')->get('New Ad'); ?>
                    </button>
                </a>
            </div>
            <table class="table table-striped" id="service-table">
                <thead>
                <tr>
                    <th><?php echo app('translator')->get('SL No.'); ?></th>
                    <th><?php echo app('translator')->get('Type'); ?></th>
                    <th><?php echo app('translator')->get('Currency'); ?></th>
                    <th><?php echo app('translator')->get('Payment Method'); ?></th>
                    <th><?php echo app('translator')->get('Margin / Fixed'); ?></th>
                    <th><?php echo app('translator')->get('Rate'); ?></th>
                    <th><?php echo app('translator')->get('Payment Window'); ?></th>
                    <th><?php echo app('translator')->get('Published'); ?></th>
                    <th><?php echo app('translator')->get('Status'); ?></th>
                    <th><?php echo app('translator')->get('Action'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $advertises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="<?php echo app('translator')->get('SL No.'); ?>"><?php echo e(++$key); ?></td>
                        <td data-label="<?php echo app('translator')->get('Type'); ?>"><span
                                class="badge <?php echo e(($item->type == 'buy')? 'bg-success' : 'bg-warning'); ?>"><?php echo app('translator')->get($item->type); ?></span>
                        </td>
                        <td data-label="<?php echo app('translator')->get('Currency'); ?>"><?php echo app('translator')->get(optional($item->fiatCurrency)->code); ?></td>
                        <td data-label="<?php echo app('translator')->get('Payment Method'); ?>">
                            <div class="d-flex flex-wrap">
                                <?php $__currentLoopData = $item->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="gateway-color"
                                          style="border-left-color: <?php echo e($gateway->color); ?>"><?php echo e($gateway->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td data-label="<?php echo app('translator')->get('Margin / Fixed'); ?>"><?php echo app('translator')->get($item->price_type); ?>
                            - <?php echo e(number_format($item->price,2)); ?> <?php echo e($item->price_type == 'margin'? '%':optional($item->fiatCurrency)->code); ?></td>
                        <td data-label="<?php echo app('translator')->get('Rate'); ?>"><?php echo app('translator')->get(number_format($item->rate,3)); ?> <?php echo e(optional($item->fiatCurrency)->code); ?>

                            /<?php echo e(optional($item->cryptoCurrency)->code); ?></td>
                        <td data-label="<?php echo app('translator')->get('Payment Window'); ?>"><?php echo app('translator')->get(optional($item->paymentWindow)->name); ?></td>
                        <td data-label="<?php echo app('translator')->get('Published'); ?>"><span
                                class="badge <?php echo e(($item->status == 1)?'bg-success':'bg-danger'); ?>"><?php echo e(($item->status == 1)?'Yes':'No'); ?></span>
                        </td>
                        <td data-label="<?php echo app('translator')->get('Status'); ?>"><span
                                class="badge <?php echo e(($item->status == 1)?'bg-success':'bg-danger'); ?>"><?php echo e(($item->status == 1)?'Enabled':'Disabled'); ?></span>
                        </td>
                        <td data-label="<?php echo app('translator')->get('Action'); ?>" class="action">
                            <div class="sidebar-dropdown-items">
                                <button
                                    type="button"
                                    class="dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="far fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="<?php echo e(route('user.feedback.list',$item->id)); ?>"
                                           class="dropdown-item">
                                            <i class="far fa-thumbs-up"></i> <?php echo app('translator')->get('Feedback'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo e(route('user.advertisements.edit',$item->id)); ?>"
                                           class="dropdown-item">
                                            <i class="far fa-edit"></i> <?php echo app('translator')->get('Edit'); ?>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="<?php echo e(route('user.trade.list','?adId='.$item->id)); ?>"
                                           class="dropdown-item">
                                            <i class="fal fa-file-spreadsheet"></i> <?php echo app('translator')->get('Trade Lists'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <?php if($item->status == 0): ?>
                                            <form action="<?php echo e(route('user.advertisements.enable',$item->id)); ?>"
                                                  method="post">
                                                <?php echo csrf_field(); ?>
                                                <button class="dropdown-item" type="submit">
                                                    <i class="far fa-eye"></i> <?php echo app('translator')->get('Enable'); ?>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form action="<?php echo e(route('user.advertisements.disable',$item->id)); ?>"
                                                  method="post">
                                                <?php echo csrf_field(); ?>
                                                <button class="dropdown-item" type="submit">
                                                    <i class="far fa-eye-slash"></i> <?php echo app('translator')->get('Disable'); ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>
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
                <?php echo e($advertises->appends($_GET)->links($theme.'partials.pagination')); ?>

            </ul>
        </nav>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/advertise/index.blade.php ENDPATH**/ ?>