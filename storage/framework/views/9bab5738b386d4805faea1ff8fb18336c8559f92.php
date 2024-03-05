<?php $__env->startSection('title','Fiat Currency List'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-body">
                    <form method="post" action="<?php echo e(route('admin.fiatControl.action')); ?>" class="needs-validation base-form">
                        <?php echo csrf_field(); ?>
                        <div class="row my-3">
                            <div class="form-group col-md-6">
                                <label class="text-dark"><?php echo app('translator')->get('Currency Layer Access Key'); ?></label>
                                <input type="text" value='<?php echo e(old('fiat_currency_api',$control->fiat_currency_api)); ?>'
                                       name='fiat_currency_api'
                                       required="required" class="form-control ">
                                <?php $__errorArgs = ['fiat_currency_api'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e(trans($message)); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="text-dark"><?php echo app('translator')->get('Rate Update'); ?></label>
                                <div class="custom-switch-btn">
                                    <input type='hidden' value='1' name='fiat_currency_status'>
                                    <input type="checkbox" name="fiat_currency_status" class="custom-switch-checkbox"
                                           id="fiat_currency_status"
                                           value="0" <?php echo e(($control->fiat_currency_status == 0)?'checked':''); ?> >
                                    <label class="custom-switch-checkbox-label" for="fiat_currency_status">
                                        <span class="custom-switch-checkbox-inner"></span>
                                        <span class="custom-switch-checkbox-switch"></span>
                                    </label>
                                </div>
                            </div>

                        </div>

                        <button type="submit"
                                class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                                    class="fas fa-save pr-2"></i> <?php echo app('translator')->get('Save Changes'); ?></span></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between mb-3">
                        <div class="col-md-12">
                            <h4 class="card-title  font-weight-bold"><?php echo app('translator')->get('Currency Layer Instructions
'); ?></h4>
                        </div>
                    </div>
                    <?php echo app('translator')->get("Currencylayer provides a simple REST API with real-time and historical exchange rates for 168 world currencies,delivering currency pairs in universally usable JSON format - compatible with any of your applications."); ?>
                    <br><br>
                    <?php echo app('translator')->get("Spot exchange rate data is retrieved from several major forex data providers in real-time, validated,processed and delivered hourly, Every 10 minutes, or even within the 60-second market window."); ?>

                    <a href="https://currencylayer.com/product" target="_blank"><?php echo app('translator')->get('Create an account'); ?>
                        <i class="fas fa-external-link-alt"></i></a>
                </div>
            </div>
        </div>
    </div>


    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="media justify-content-between mb-4">
                <a href="<?php echo e(route('admin.createFiat')); ?>" class="btn btn-sm btn-primary mr-2">
                    <span><i class="fa fa-plus-circle"></i> <?php echo app('translator')->get('Add New'); ?></span>
                </a>
            </div>

            <div class="dropdown mb-2 text-right">
                <button class="btn btn-sm  btn-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span><i class="fas fa-bars pr-2"></i> <?php echo app('translator')->get('Action'); ?></span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" type="button" data-toggle="modal"
                            data-target="#all_active"><?php echo app('translator')->get('Active'); ?></button>
                    <button class="dropdown-item" type="button" data-toggle="modal"
                            data-target="#all_inactive"><?php echo app('translator')->get('DeActive'); ?></button>
                    <button class="dropdown-item" type="button" data-toggle="modal"
                            data-target="#rate_update"><?php echo app('translator')->get('Rate Update'); ?></button>
                </div>
            </div>


            <div class="table-responsive">
                <table class="categories-show-table table table-hover table-striped table-bordered" id="zero_config">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="text-center">
                            <input type="checkbox" class="form-check-input check-all tic-check" name="check-all"
                                   id="check-all">
                            <label for="check-all"></label>
                        </th>

                        <th scope="col"><?php echo app('translator')->get('SL No.'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Name'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Rate'); ?> <small>(<?php echo app('translator')->get('per USD'); ?>)</small></th>
                        <th scope="col" class="text-center"><?php echo app('translator')->get('Status'); ?></th>
                        <th scope="col" class="text-center"><?php echo app('translator')->get('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $currency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" id="chk-<?php echo e($item->id); ?>"
                                       class="form-check-input row-tic tic-check" name="check" value="<?php echo e($item->id); ?>"
                                       data-id="<?php echo e($item->id); ?>">
                                <label for="chk-<?php echo e($item->id); ?>"></label>
                            </td>

                            <td data-label="<?php echo app('translator')->get('SL No.'); ?>"><?php echo e($loop->index + 1); ?></td>
                            <td data-label="<?php echo app('translator')->get('Name'); ?>">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img
                                            src="<?php echo e(getFile(config('location.currency.path') . $item->image)); ?>"
                                            alt="user" class="rounded-circle" width="35" height="35"></div>
                                    <div class="mr-3">
                                        <h5 class="text-dark mb-0 font-weight-medium"><?php echo app('translator')->get($item->name); ?></h5>
                                    </div>
                                </div>
                            </td>
                            <td data-label="<?php echo app('translator')->get('Rate'); ?>">
                                <span class="text-dark font-weight-bold"><?php echo e($item->symbol); ?><?php echo e($item->rate+0); ?>

                            </td>
                            <td data-label="<?php echo app('translator')->get('Status'); ?>">
                                <?php echo app('translator')->get($item->statusMessage); ?>
                            </td>

                            <td data-label="<?php echo app('translator')->get('Action'); ?>">

                                <div class="dropdown show">
                                    <a class="dropdown-toggle p-3" href="#" id="dropdownMenuLink" data-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                        <a class="dropdown-item" href="<?php echo e(route('admin.editFiat',$item->id)); ?>">
                                            <i class="fa fa-edit text-warning pr-2"
                                               aria-hidden="true"></i> <?php echo app('translator')->get('Edit'); ?>
                                        </a>

                                        <a class="dropdown-item notiflix-confirm" href="javascript:void(0)"
                                           data-target="#delete-modal"
                                           data-route="<?php echo e(route('admin.deleteFiat',$item->id)); ?>"
                                           data-toggle="modal"
                                        >
                                            <i class="fa fa-trash-alt text-danger pr-2"
                                               aria-hidden="true"></i> <?php echo app('translator')->get('Delete'); ?>
                                        </a>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr class="text-center">
                            <td colspan="8"><?php echo app('translator')->get('No Data Found'); ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- All Active Modal -->
    <div class="modal fade" id="all_active" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><?php echo app('translator')->get('Active Confirmation'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p><?php echo app('translator')->get("Are you really want to active the currencies"); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span><?php echo app('translator')->get('No'); ?></span></button>
                    <form action="" method="post">
                        <?php echo csrf_field(); ?>
                        <a href="" class="btn btn-primary active-yes"><span><?php echo app('translator')->get('Yes'); ?></span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- All Inactive Modal -->
    <div class="modal fade" id="all_inactive" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><?php echo app('translator')->get('DeActive Confirmation'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p><?php echo app('translator')->get("Are you really want to Deactive the Currencies"); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span><?php echo app('translator')->get('No'); ?></span></button>
                    <form action="" method="post">
                        <?php echo csrf_field(); ?>
                        <a href="" class="btn btn-primary inactive-yes"><span><?php echo app('translator')->get('Yes'); ?></span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Rate Update Modal -->
    <div class="modal fade" id="rate_update" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><?php echo app('translator')->get('Rate Update Confirmation'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p><?php echo app('translator')->get("Are you really want to Update Rate"); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span><?php echo app('translator')->get('No'); ?></span></button>
                    <form action="" method="post">
                        <?php echo csrf_field(); ?>
                        <a href="" class="btn btn-primary rate-update"><span><?php echo app('translator')->get('Yes'); ?></span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel"><?php echo app('translator')->get('Delete Confirmation'); ?>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo app('translator')->get('Are you sure to delete this?'); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                    <form action="" method="post" class="deleteRoute">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('delete'); ?>
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Yes'); ?></button>
                    </form>
                </div>
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


    <?php if($errors->any()): ?>
        <?php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        ?>
        <script>
            "use strict";
            <?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            Notiflix.Notify.Failure("<?php echo e(trans($error)); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
    <?php endif; ?>

    <script>
        'use strict'
        $(document).on('click', '.notiflix-confirm', function () {
            var route = $(this).data('route');
            $('.deleteRoute').attr('action', route)
        })

        $(document).on('click', '#check-all', function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $(document).on('change', ".row-tic", function () {
            let length = $(".row-tic").length;
            let checkedLength = $(".row-tic:checked").length;
            if (length == checkedLength) {
                $('#check-all').prop('checked', true);
            } else {
                $('#check-all').prop('checked', false);
            }
        });

        //dropdown menu is not working
        $(document).on('click', '.dropdown-menu', function (e) {
            e.stopPropagation();
        });


        //multiple active
        $(document).on('click', '.active-yes', function (e) {
            e.preventDefault();
            var allVals = [];
            $(".row-tic:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });

            var strIds = allVals;

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "<?php echo e(route('admin.fiat-active')); ?>",
                data: {strIds: strIds},
                datatType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();

                },
            });
        });

        //multiple deactive
        $(document).on('click', '.inactive-yes', function (e) {
            e.preventDefault();
            var allVals = [];
            $(".row-tic:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });

            var strIds = allVals;
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "<?php echo e(route('admin.fiat-deactive')); ?>",
                data: {strIds: strIds},
                datatType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();
                }
            });
        });
        //update rate
        $(document).on('click', '.rate-update', function (e) {
            e.preventDefault();
            var allVals = [];
            $(".row-tic:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });

            var strIds = allVals;

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "<?php echo e(route('admin.fiatRate')); ?>",
                data: {strIds: strIds},
                datatType: 'json',
                type: "post",
                success: function (data) {
                    window.location.reload();
                },
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/currency/fiat/list.blade.php ENDPATH**/ ?>