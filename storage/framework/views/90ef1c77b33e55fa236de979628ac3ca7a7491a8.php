<?php $__env->startSection('title',trans($title)); ?>
<?php $__env->startSection('content'); ?>

    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <div class="col-md-12">
                    <div class="search-bar">
                        <form action="<?php echo e(route('user.payout.history.search')); ?>" method="get">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <div class="form-group input-box mb-2">
                                        <input type="text" name="name" value="<?php echo e(@request()->name); ?>"
                                               class="form-control"
                                               placeholder="<?php echo app('translator')->get('Type Here'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group input-box mb-2">
                                        <select name="status" class="form-control">
                                            <option value=""><?php echo app('translator')->get('All Payment'); ?></option>
                                            <option value="1"
                                                    <?php if(@request()->status == '1'): ?> selected <?php endif; ?>><?php echo app('translator')->get('Pending Payment'); ?></option>
                                            <option value="2"
                                                    <?php if(@request()->status == '2'): ?> selected <?php endif; ?>><?php echo app('translator')->get('Complete Payment'); ?></option>
                                            <option value="3"
                                                    <?php if(@request()->status == '3'): ?> selected <?php endif; ?>><?php echo app('translator')->get('Rejected Payment'); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group input-box mb-2">
                                        <input type="date" class="form-control" name="date_time"
                                               id="datepicker"/>
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group mb-2 h-fill">
                                        <button type="submit" class="btn-custom">
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
                        <th scope="col"><?php echo app('translator')->get('Transaction ID'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Wallet Address'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Amount'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Charge'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Status'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Time'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $payoutLog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td data-label="#<?php echo app('translator')->get('Transaction ID'); ?>"><?php echo e($item->trx_id); ?></td>
                            <td data-label="<?php echo app('translator')->get('Wallet Address'); ?>"><?php echo e($item->wallet_address); ?></td>
                            <td data-label="<?php echo app('translator')->get('Amount'); ?>">
                                <strong><?php echo e(getAmount($item->amount,8)); ?> <?php echo app('translator')->get($item->code); ?></strong>
                            </td>
                            <td data-label="<?php echo app('translator')->get('Charge'); ?>">
                                <strong><?php echo e(getAmount($item->charge,8)); ?> <?php echo app('translator')->get($item->code); ?></strong>
                            </td>

                            <td data-label="<?php echo app('translator')->get('Status'); ?>">
                                <?php if($item->status == 1): ?>
                                    <span class="badge bg-warning"><?php echo app('translator')->get('Pending'); ?></span>
                                <?php elseif($item->status == 2): ?>
                                    <span class="badge bg-success"><?php echo app('translator')->get('Complete'); ?></span>
                                <?php elseif($item->status == 3): ?>
                                    <span class="badge bg-danger"><?php echo app('translator')->get('Cancel'); ?></span>
                                <?php endif; ?>
                            </td>

                            <td data-label="<?php echo app('translator')->get('Time'); ?>">
                                <?php echo e(dateTime($item->created_at, 'd M Y h:i A')); ?>

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
                    <?php echo e($payoutLog->appends($_GET)->links($theme.'partials.pagination')); ?>

                </ul>
            </nav>
        </div>
    </div>

    <div id="infoModal" class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content form-block">

                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Details'); ?></h5>
                    <button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group ">
                        <li class="list-group-item bg-transparent"><?php echo app('translator')->get('Transactions'); ?> : <span class="trx"></span>
                        </li>
                        <li class="list-group-item bg-transparent"><?php echo app('translator')->get('Admin Feedback'); ?> : <span
                                class="feedback"></span></li>
                    </ul>
                    <div class="payout-detail">

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light closeModal" data-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>

    <script>
        "use strict";

        $(document).ready(function () {
            $('.infoButton').on('click', function () {
                var infoModal = $('#infoModal');
                infoModal.find('.trx').text($(this).data('trx_id'));
                infoModal.find('.feedback').text($(this).data('feedback'));
                var list = [];
                var information = Object.entries($(this).data('information'));

                var ImgPath = "<?php echo e(asset(config('location.withdrawLog.path'))); ?>/";
                var result = ``;
                for (var i = 0; i < information.length; i++) {
                    if (information[i][1].type == 'file') {
                        result += `<li class="list-group-item bg-transparent">
                                            <span class="font-weight-bold "> ${information[i][0].replaceAll('_', " ")} </span> : <img class="w-100"src="${ImgPath}/${information[i][1].field_name}" alt="..." class="w-100">
                                        </li>`;
                    } else {
                        result += `<li class="list-group-item bg-transparent">
                                            <span class="font-weight-bold "> ${information[i][0].replaceAll('_', " ")} </span> : <span class="font-weight-bold ml-3">${information[i][1].field_name}</span>
                                        </li>`;
                    }
                }

                if (result) {
                    infoModal.find('.payout-detail').html(`<br><strong class="my-3"><?php echo app('translator')->get('Payment Information'); ?></strong>  ${result}`);
                } else {
                    infoModal.find('.payout-detail').html(`${result}`);
                }
                infoModal.modal('show');
            });


            $('.closeModal').on('click', function (e) {
                $("#infoModal").modal("hide");
            });
        });

    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/payout/log.blade.php ENDPATH**/ ?>