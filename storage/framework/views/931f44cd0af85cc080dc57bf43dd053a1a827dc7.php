<?php $__env->startSection('title', trans($page_title)); ?>
<?php $__env->startSection('content'); ?>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered no-wrap" id="zero_config">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col"><?php echo app('translator')->get('Name'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Type'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Status'); ?></th>
                        
                            <th scope="col"><?php echo app('translator')->get('Action'); ?></th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td data-label="Name">
                                <a href="javascript:void(0)">
                                    <div class="d-lg-flex d-block align-items-center ">
                                        <div class="mr-3"><img
                                                src="<?php echo e(getFile(config('location.payoutMethod.path').$method->image)); ?>"
                                                alt="user" class="rounded-circle" width="45" height="45">
                                        </div>
                                        <div class="">
                                            <h5 class="text-dark mb-0 font-16 font-weight-medium">
                                                <?php echo e($method->name); ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </td>

                            <td data-label="<?php echo app('translator')->get('Type'); ?>">
                                <?php if($method->is_automatic == 1): ?>
                                    <span class="text-success font-weight-bold"><?php echo app('translator')->get('Automatic'); ?></span>
                                <?php else: ?>
                                    <span class="text-warning font-weight-bold"><?php echo app('translator')->get('Manual'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td data-label="<?php echo app('translator')->get('Status'); ?>">
                            <span
                                class="badge badge-pill badge-<?php echo e(($method->status == 1) ?'success' : 'danger'); ?>"><?php echo e(($method->status == 1) ?trans('Active') : trans('Deactive')); ?></span>
                            </td>

                               <td data-label="<?php echo app('translator')->get('Action'); ?>">
                                    <a href="<?php echo e(route('admin.payout.method.edit', $method->id)); ?>"
                                       class="btn btn-sm btn-primary"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       data-original-title="<?php echo app('translator')->get('Edit Payment Methods Info'); ?>">
                                        <i class="fa fa-edit"></i></a>
                                    <?php if($method->status == 0): ?>
                                        <button class="btn btn-sm  btn-success enable"
                                                data-route="<?php echo e(route('admin.payout.methodStatus',$method->id)); ?>"
                                                data-target="#statusChange"
                                                data-toggle="modal"><?php echo app('translator')->get('Enable'); ?></button>
                                    <?php endif; ?>
                                </td>
                            
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center text-danger" colspan="8">
                                <?php echo app('translator')->get('No Data Found'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusChange" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel"><?php echo app('translator')->get('Status Change Confirmation'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <form method="POST" class="actionRoute" action="">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="add-text">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->get('Close'); ?>
                        </button>
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Confirm'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link href="<?php echo e(asset('assets/admin/css/dataTables.bootstrap4.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('assets/admin/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/admin/js/datatable-basic.init.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('extra-script'); ?>
    <script>
        'use strict'
        $(document).on('click', '.enable', function () {
            $('.add-text').html('');
            let route = $(this).data('route');
            $('.add-text').append(`Are you sure want to enable this payout method`)
            $('.actionRoute').attr('action', route);
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/payout_methods/index.blade.php ENDPATH**/ ?>