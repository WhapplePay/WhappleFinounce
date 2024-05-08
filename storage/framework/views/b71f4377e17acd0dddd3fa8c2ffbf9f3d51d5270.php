<?php $__env->startSection('title'); ?>
    <?php echo e($page_title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary shadow">
                    <div class="card-body">
                        <form method="post" action="<?php echo e(route('admin.update.payment.methods', $method->id)); ?>"
                              class="needs-validation base-form" novalidate="" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('put'); ?>
                            <div class="my-2 section-title">
                                <?php echo app('translator')->get('Edit'); ?> <?php echo e(strtoupper($method->name)); ?>

                            </div>
                            <div class="row">
                                <div class="form-group col-md-4 col-6">
                                    <label><?php echo app('translator')->get('Name'); ?></label>
                                    <input type="text" class="form-control "
                                           name="name"
                                           value="<?php echo e(old('name', $method->name ? : '')); ?>">
                                    <?php if($errors->has('name')): ?>
                                        <span class="invalid-text">
                                                <?php echo e($errors->first('name')); ?>

                                            </span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group col-md-4 col-4">
                                    <label><?php echo app('translator')->get('Border Color'); ?></label>
                                    <input type="color" class="form-control "
                                           name="color" value="<?php echo e($method->color); ?>">
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
                                             src="<?php echo e(getFile(config('location.gateway.path').$method->image)); ?>"
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
                                        <input type="checkbox" name="status" class="custom-switch-checkbox" id="status"
                                               value="0" <?php if ($method->status == 0):echo 'checked'; endif ?> >
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
                                <?php if($method->input_form): ?>
                                    <?php $__currentLoopData = $method->input_form; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group">

                                                    <input name="field_name[]" class="form-control"
                                                           type="text" value="<?php echo e($v->label); ?>" required
                                                           placeholder="<?php echo e(trans('Field Name')); ?>">

                                                    <select name="type[]" class="form-control">
                                                        <option value="text"
                                                                <?php if($v->type == 'text'): ?> selected <?php endif; ?>><?php echo e(trans('Input Text')); ?></option>
                                                        <option value="textarea"
                                                                <?php if($v->type == 'textarea'): ?> selected <?php endif; ?>><?php echo e(trans('Textarea')); ?></option>
                                                    </select>

                                                    <select name="validation[]" class="form-control  ">
                                                        <option value="required"
                                                                <?php if($v->validation == 'required'): ?> selected <?php endif; ?>><?php echo e(trans('Required')); ?></option>
                                                        <option value="nullable"
                                                                <?php if($v->validation == 'nullable'): ?> selected <?php endif; ?>><?php echo e(trans('Optional')); ?></option>
                                                    </select>

                                                    <span class="input-group-btn">
                                                    <button class="btn btn-danger  delete_desc" type="button">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                            <button type="submit"
                                    class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><?php echo app('translator')->get('Save Changes'); ?>
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
            setCurrency();
            $(document).on('change', '.currency-change', function () {
                setCurrency();
            });

            function setCurrency() {
                let currency = $('.currency-change').val();
                let fiatYn = $('.currency-change option:selected').attr('data-fiat');
                if (fiatYn == 0) {
                    $('.set-currency').text(currency);
                } else {
                    $('.set-currency').text('USD');
                }
            }

            $(document).on('click', '.copy-btn', function () {
                var _this = $(this)[0];
                var copyText = $(this).parents('.input-group-append').siblings('input');
                $(copyText).prop('disabled', false);
                copyText.select();
                document.execCommand("copy");
                $(copyText).prop('disabled', true);
                $(this).text('Coppied');
                setTimeout(function () {
                    $(_this).text('');
                    $(_this).html('<i class="fas fa-copy"></i>');
                }, 500)
            });

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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/payment_methods/edit.blade.php ENDPATH**/ ?>