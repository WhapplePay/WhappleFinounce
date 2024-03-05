<?php $__env->startSection('title'); ?>
    <?php echo e($page_title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary shadow">
                    <div class="card-body">
                        <form method="post" action="<?php echo e(route('admin.store.payment.methods')); ?>"
                              class="needs-validation base-form" novalidate="" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="form-group col-md-4 col-6">
                                    <label><?php echo app('translator')->get('Name'); ?></label>
                                    <input type="text" class="form-control "
                                           name="name" value="<?php echo e(old('name')); ?>">
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <label><?php echo app('translator')->get('Border Color'); ?></label>
                                    <input type="color" class="form-control "
                                           name="color" value="<?php echo e(old('color')); ?>">
                                    <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="image-input ">
                                        <label for="image-upload" id="image-label"><i class="fas fa-upload"></i></label>
                                        <input type="file" name="image" placeholder="<?php echo app('translator')->get('Choose image'); ?>" id="image">
                                        <img id="image_preview_container" class="preview-image"
                                             src=""
                                             alt="preview image">
                                    </div>
                                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group col-lg-3 col-md-4">
                                    <label><?php echo app('translator')->get('Status'); ?></label>

                                    <div class="custom-switch-btn">
                                        <input type='hidden' value='1' name='status'>
                                        <input type="checkbox" name="status" class="custom-switch-checkbox"
                                               id="status"
                                               value="0">
                                        <label class="custom-switch-checkbox-label" for="status">
                                            <span class="custom-switch-checkbox-inner"></span>
                                            <span class="custom-switch-checkbox-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <a href="javascript:void(0)" class="btn btn-sm btn-success float-right mt-3"
                                           id="generate"><i
                                                class="fa fa-plus-circle"></i> <?php echo app('translator')->get('Add Field'); ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row addedField">
                            </div>
                            <button type="submit"
                                    class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><?php echo app('translator')->get('Create'); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
    <script>
        "use strict";

        $(document).ready(function () {
            $('#image').on('change', function () {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });

        $(document).on('click', '#generate', function () {
            var form = `<div class="col-md-12">
        <div class="form-group">
            <div class="input-group">
                <input name="field_name[]" class="form-control " type="text" value="" required
                       placeholder="<?php echo app('translator')->get("Field Name"); ?>">

                <select name="type[]" class="form-control ">
                    <option value="text"><?php echo app('translator')->get("Input Text"); ?></option>
                    <option value="textarea"><?php echo app('translator')->get("Textarea"); ?></option>
                </select>

                <select name="validation[]" class="form-control  ">
                    <option value="required"><?php echo app('translator')->get('Required'); ?></option>
                    <option value="nullable"><?php echo app('translator')->get('Optional'); ?></option>
                </select>

                <span class="input-group-btn">
                    <button class="btn btn-danger  delete_desc" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>`;
            $('.addedField').append(form)

        })
        $(document).on('click', '.delete_desc', function () {
            $(this).closest('.input-group').parent().remove();
        });

    </script>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/payment_methods/create.blade.php ENDPATH**/ ?>