<?php $__env->startSection('title',trans('Payment Credential')); ?>

<?php $__env->startSection('content'); ?>
    <!-- main -->
    <div class="container-fluid">
        <div class="main row">
            <!-- edit profile section -->
            <div class="row">
                <div class="col-md-8">
                    <div class="edit-profile-section">
                        <h6 class="title mb-4"><?php echo app('translator')->get('Select Payment Method'); ?></h6>
                        <div class="table-parent table-responsive">
                            <table class="table  " id="service-table">

                                <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $infos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="active">
                                        <td data-label="<?php echo app('translator')->get('Payment method'); ?>">
                                            <span class="gateway-color"
                                                  style="border-left-color: <?php echo e(optional($item->gateway)->color); ?>"><?php echo e(optional($item->gateway)->name); ?></span>
                                        </td>

                                        <td data-label="<?php echo app('translator')->get('Information'); ?>">

                                            <div class="d-lg-flex d-block align-items-center">
                                                <div class="">
                                                    <?php $__currentLoopData = collect($item->information)->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inKey => $inData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($loop->first): ?>
                                                            <h6 class="text-white mb-0 text-lowercase">
                                                                <?php echo e($inData->fieldValue); ?></h6>
                                                        <?php else: ?>
                                                            <span
                                                                class="text-muted font-10"><?php echo e($inData->fieldValue); ?> </span>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>

                                        </td>
                                        <td data-label="<?php echo app('translator')->get('Action'); ?>" class="action">
                                            <a href="javascript:void(0)"
                                               data-name="<?php echo e(optional($item->gateway)->name); ?>"
                                               data-route="<?php echo e(route('user.sellCurrencies.gatewayInfoUpdate',$item->id)); ?>"
                                               data-input_form="<?php echo e(json_encode($item->information)); ?>"
                                               class="btn btn-sm btn-outline-warning  viewData"
                                               data-bs-target="#viewModal"
                                               data-bs-toggle="modal">

                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="javascript:void(0)"
                                               class="btn btn-sm btn-outline-success selectBtn"
                                               data-gateway_id="<?php echo e($item->gateway_id); ?>"
                                               data-adds_id="<?php echo e($advertise->id); ?>"
                                               data-id="<?php echo e($item->id); ?>">
                                                <i class="fa fa-check-circle"></i>
                                            </a>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if($advertise->gateways): ?>
                    <div class="col-md-4">
                        <div class="edit-profile-section">
                            <h6 class="title"><?php echo app('translator')->get('Supported Payment Methods'); ?></h6>
                            <div class="list-group" id="list-tab" role="tablist">
                                <?php $__currentLoopData = $advertise->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a class="bg-transparent bgDark list-group-item list-group-item-action text-capitalize addGatewayInfo"
                                       href="javascript:void(0)"
                                       data-name="<?php echo e($item->name); ?>"
                                       data-id="<?php echo e($item->id); ?>"
                                       data-input_form="<?php echo e(json_encode($item->input_form)); ?>"
                                       data-bs-target="#addModal" data-bs-toggle="modal"><i
                                            class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?> <?php echo app('translator')->get($item->name); ?></a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title addModalLabel" id="addModalLabel"></h6>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('user.sellCurrencies.gatewayInfoSave')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" name="gatewayId" value="" class="gatewayId">
                        <div class="row g-4  withdraw-detail">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                        <button type="submit" class="btn-custom"><?php echo app('translator')->get('Yes'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title viewModalLabel" id="viewModalLabel"></h6>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="" method="post" class="viewAction">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row g-4  view-detail">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                        <button type="submit" name="submitBtn" value="delete"
                                class="btn btn-danger red"><?php echo app('translator')->get('Delete'); ?></button>
                        <button type="submit" name="submitBtn" value="update"
                                class="btn btn-success"><?php echo app('translator')->get('Update'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
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
        "use strict";

        $(document).on('click', '.viewData', function () {
            $('.view-detail').html('');
            var $name = $(this).data('name');
            var route = $(this).data('route');
            $('.viewAction').attr('action', route);
            var $input_form = Object.entries($(this).data('input_form'));
            $('.viewModalLabel').text(`Update ${$name} Information`)

            var list = [];
            $input_form.map(function (item, i) {
                if (item[1].type == "text") {
                    var singleInfo = `<div class="input-box col-md-12">
                                <label>${item[1].label}
                                        ${(item[1].validation == "required") ? '<span class="text-danger">*</span>' : ''}
                                </label>
                                <input type="text" name="${item[1].name}" value="${item[1].fieldValue}"
                                       placeholder="${(item[1].validation != "required") ? 'optional' : ' ' + item[1].label}"
                                       class="form-control" ${(item[1].validation == "required") ? 'required' : ''}>
                            </div>`;
                } else {
                    var singleInfo = `<div class="input-box col-md-12">
                                <label>${item[1].label}
                                    ${(item[1].validation == "required") ? '<span class="text-danger">*</span>' : ''}
                                </label>
                                <textarea type="text" name="${item[1].name}"
                                          class="form-control" ${(item[1].validation == "required") ? 'required' : ''}
                                       placeholder="${(item[1].validation != "required") ? 'optional' : ' ' + item[1].label}">${item[1].fieldValue}</textarea>
                            </div>`;
                }
                list[i] = singleInfo
            });
            $('.view-detail').html(list);

        });

        $(document).on('click', '.addGatewayInfo', function () {
            $('.withdraw-detail').html('');
            var $name = $(this).data('name');
            var $id = $(this).data('id');
            $('.gatewayId').val($id);
            var $input_form = Object.entries($(this).data('input_form'));

            $('.addModalLabel').text(`Add ${$name} Information`)

            var list = [];
            $input_form.map(function (item, i) {
                if (item[1].type == "text") {
                    var singleInfo = `<div class="input-box col-md-12">
                                <label>${item[1].label}
                                        ${(item[1].validation == "required") ? '<span class="text-danger">*</span>' : ''}
                                </label>
                                <input type="text" name="${item[1].name}"
                                       placeholder="${(item[1].validation != "required") ? 'optional' : ' ' + item[1].label}"
                                       class="form-control" ${(item[1].validation == "required") ? 'required' : ''}>
                            </div>`;
                } else {
                    var singleInfo = `<div class="input-box col-md-12">
                                <label>${item[1].label}
                                    ${(item[1].validation == "required") ? '<span class="text-danger">*</span>' : ''}
                                </label>
                                <textarea type="text" name="${item[1].name}"
                                          class="form-control" ${(item[1].validation == "required") ? 'required' : ''}
                                       placeholder="${(item[1].validation != "required") ? 'optional' : ' ' + item[1].label}"></textarea>
                            </div>`;
                }
                list[i] = singleInfo
            });
            $('.withdraw-detail').html(list);
        });
        $(document).on('click', '.selectBtn', function () {
            var gateway_id = $(this).data('gateway_id');
            var id = $(this).data('id');
            var adds_id = $(this).data('adds_id');
            $.ajax({
                url: "<?php echo e(route('user.sellCurrencies.gatewaySelect')); ?>",
                method: 'post',
                data: {
                    gateway_id: gateway_id, id: id, adds_id: adds_id,
                },
                success: function (response) {
                    window.location.href = response.url
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/paymentCredential/index.blade.php ENDPATH**/ ?>