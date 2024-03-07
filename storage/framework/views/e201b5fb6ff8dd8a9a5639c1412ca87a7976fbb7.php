<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($page_title); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="page-header card card-primary m-0 m-md-4 my-4 m-md-0 p-5 shadow">
        <form action="<?php echo e(route('admin.payment.search')); ?>" method="get">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="name" value="<?php echo e(@request()->name); ?>" class="form-control"
                               placeholder="<?php echo app('translator')->get('User Information'); ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="trx" value="<?php echo e(@request()->trx); ?>" class="form-control"
                               placeholder="<?php echo app('translator')->get('Trx Number'); ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="date" class="form-control" name="date_time" id="datepicker"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn waves-effect waves-light btn-primary"><i
                                class="fas fa-search"></i> <?php echo app('translator')->get('Search'); ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="categories-show-table table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col"><?php echo app('translator')->get('Date'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Trx Number'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('User'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Amount + Charge'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Status'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $funds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $fund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td data-label="<?php echo app('translator')->get('Date'); ?>"> <?php echo e(dateTime($fund->created_at,'d M,Y H:i')); ?></td>
                            <td data-label="<?php echo app('translator')->get('Trx Number'); ?>"
                                class="font-weight-bold text-uppercase"><?php echo e($fund->trx); ?></td>
                            <td data-label="<?php echo app('translator')->get('Username'); ?>">
                                <a href="<?php echo e(route('admin.user-edit',$fund->user_id )); ?>">
                                    <div class="d-flex no-block align-items-center">
                                        <div class="mr-3"><img
                                                src="<?php echo e(getFile(config('location.user.path') . optional($fund->user)->image)); ?>"
                                                alt="user" class="rounded-circle" width="35" height="35"></div>
                                        <div class="mr-3">
                                            <h5 class="text-dark mb-0 font-16 font-weight-medium"><?php echo app('translator')->get(optional($fund->user)->username); ?></h5>
                                            <span
                                                class="text-muted font-14"><?php echo e(optional($fund->user)->email); ?></span>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td data-label="<?php echo app('translator')->get('Amount + Charge'); ?>"
                                class="font-weight-bold"
                                title="amount"><?php echo e(optional($fund->crypto)->code); ?> <?php echo e(getAmount($fund->amount,8)); ?>

                                + <span class="text-danger"
                                        title="<?php echo app('translator')->get('charge'); ?>"><?php echo e(getAmount($fund->charge,8)); ?> <?php echo e(optional($fund->crypto)->code); ?></span>
                                <br>
                                <strong title="<?php echo app('translator')->get('Amount with charge'); ?>">
                                    <?php echo e(getAmount($fund->amount + $fund->charge, 8)); ?> <?php echo e(__(optional($fund->crypto)->code)); ?>

                                </strong>
                            </td>
                            <td data-label="<?php echo app('translator')->get('Status'); ?>">
                                <?php if($fund->status == 1): ?>
                                    <span class="badge badge-success"><?php echo app('translator')->get('Success'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td data-label="<?php echo app('translator')->get('Action'); ?>">
                                <button class="btn btn-sm btn-outline-primary details" data-resource="<?php echo e($fund); ?>"
                                        data-target="#detailsModal"
                                        data-toggle="modal">
                                    <?php echo app('translator')->get('Details'); ?></button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center text-dark" colspan="100%">
                                <?php echo app('translator')->get('No Data Found'); ?>
                            </td>
                        </tr>

                    <?php endif; ?>
                    </tbody>
                </table>
                <?php echo e($funds->appends($_GET)->links('partials.pagination')); ?>

            </div>
        </div>
    </div>

    <!-- Modal for Edit button -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel"><?php echo app('translator')->get('Deposit Information'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <form>
                    <div class="modal-body">
                        <ul class="rate">
                            <li><?php echo app('translator')->get('Date:'); ?> <span class="caption date"></span></li>
                            <li class="my-2"><?php echo app('translator')->get('Wallet Address:'); ?> <span class="caption walletAd"></span>
                            </li>
                            <li><?php echo app('translator')->get('Transaction Number:'); ?> <span class="caption trx"></span></li>
                            <li class="my-2"><?php echo app('translator')->get('Username:'); ?> <span class="caption text-info username">2</span></li>
                            <li><?php echo app('translator')->get('Amount:'); ?> <span class="caption amount"></span></li>
                            <li class="my-2"><?php echo app('translator')->get('Charge:'); ?> <span class="caption text-danger charge"></span></li>
                            <li class="my-2"><?php echo app('translator')->get('Payable:'); ?> <span class="caption text-success payable"></span></li>
                            <li class="mb-4"><?php echo app('translator')->get('Status:'); ?> <span class="caption status"></span></li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        "use strict";
        $(document).ready(function () {
            $('select[name=status]').select2({
                selectOnClose: true
            });
        });
        $(document).on("click", '.details', function (e) {
            var obj = $(this).data('resource');
            $('.date').text(moment(obj.created_at).format('LLL'));
            $('.walletAd').text(obj.wallet_address);
            $('.trx').text(obj.trx);
            $('.username').text(obj.user.username);
            $('.amount').text(`${obj.amount} ${obj.crypto.code}`);
            $('.charge').text(`${obj.charge} ${obj.crypto.code}`);
            $('.payable').text(`${obj.final_amount} ${obj.crypto.code}`);

            if (obj.status == 1) {
                $('.status').addClass('badge badge-success').text('Success');
            }
            console.log(obj);
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/payment/logs.blade.php ENDPATH**/ ?>