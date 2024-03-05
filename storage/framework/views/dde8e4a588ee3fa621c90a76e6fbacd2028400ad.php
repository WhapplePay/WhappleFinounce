<?php $__env->startSection('title',trans($page_title)); ?>
<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-12">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-body">

                    <div class="media mb-4 justify-content-end">
                        <a href="<?php echo e(route('admin.payout.method')); ?>" class="btn btn-sm  btn-primary mr-2">
                            <span><i class="fas fa-arrow-left"></i> <?php echo app('translator')->get('Back'); ?></span>
                        </a>
                    </div>


                    <form method="post" action="<?php echo e(route('admin.payout.method.edit', $payoutMethod->id)); ?>"
                          enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="form-group col-md-6 col-6">
                                <label><?php echo e(trans('Name')); ?></label>
                                <input type="text" class="form-control"
                                       name="name"
                                       value="<?php echo e(old('name', $payoutMethod->name ?? '')); ?>" required>

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
                        </div>

                        <?php if($payoutMethod->parameters): ?>
                            <div class="row mt-4">
                                <?php $__currentLoopData = $payoutMethod->parameters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $parameter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                for="<?php echo e($key); ?>"><?php echo e(__(strtoupper(str_replace('_',' ', $key)))); ?></label>
                                            <input type="text" name="<?php echo e($key); ?>"
                                                   value="<?php echo e(old($key, $parameter)); ?>"
                                                   id="<?php echo e($key); ?>"
                                                   class="form-control <?php $__errorArgs = [$key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <div class="invalid-feedback">
                                                <?php $__errorArgs = [$key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo app('translator')->get($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                        <div class="row justify-content-between">
                            <div class="col-sm-6 col-md-3">
                                <div class="image-input ">
                                    <label for="image-upload" id="image-label"><i class="fas fa-upload"></i></label>
                                    <input type="file" name="image" placeholder="<?php echo app('translator')->get('Choose image'); ?>" id="image">
                                    <img id="image_preview_container" class="preview-image"
                                         src="<?php echo e(getFile(config('location.payoutMethod.path').$payoutMethod->image)); ?>"
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
                        </div>
                        <div class="row mt-3">
                            <?php if($payoutMethod->is_sandbox): ?>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('Test Environment'); ?></label>
                                        <div class="custom-switch-btn">
                                            <input type='hidden' value='1' name='environment'>
                                            <input type="checkbox" name="environment" class="custom-switch-checkbox"
                                                   id="environment"
                                                   value="0" <?php echo e(($payoutMethod->environment == 0) ? 'checked':''); ?>>
                                            <label class="custom-switch-checkbox-label" for="environment">
                                                <span class="custom-switch-checkbox-inner"></span>
                                                <span class="custom-switch-checkbox-switch"></span>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row addedField">
                            <?php if($payoutMethod->input_form): ?>
                                <?php $__currentLoopData = $payoutMethod->input_form; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group">

                                                <input name="field_name[]" class="form-control"
                                                       type="text" value="<?php echo e($v->field_level); ?>" required
                                                       placeholder="<?php echo e(trans('Field Name')); ?>">

                                                <select name="type[]" class="form-control  ">
                                                    <option value="text"
                                                            <?php if($v->type == 'text'): ?> selected <?php endif; ?>><?php echo e(trans('Input Text')); ?></option>
                                                    <option value="textarea"
                                                            <?php if($v->type == 'textarea'): ?> selected <?php endif; ?>><?php echo e(trans('Textarea')); ?></option>
                                                    <option value="file"
                                                            <?php if($v->type == 'file'): ?> selected <?php endif; ?>><?php echo e(trans('File upload')); ?></option>
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
                                class="btn  btn-primary btn-block mt-3"><?php echo app('translator')->get('Save Changes'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
    <script>
        $(document).ready(function (e) {
            "use strict";

            $("#generate").on('click', function () {
                var form = `<div class="col-md-12">
        <div class="form-group">
            <div class="input-group">
                <input name="field_name[]" class="form-control " type="text" value="" required
                       placeholder="<?php echo app('translator')->get("Field Name"); ?>">

                <select name="type[]" class="form-control ">
                    <option value="text"><?php echo app('translator')->get("Input Text"); ?></option>
                    <option value="textarea"><?php echo app('translator')->get("Textarea"); ?></option>
                    <option value="file"><?php echo app('translator')->get("File upload"); ?></option>
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
            });


            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.input-group').parent().remove();
            });


            $('#image').on('change',function () {
                var reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $(document).ready(function () {
                $('select').select2({
                    selectOnClose: true
                });
            });
        });

    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/payout_methods/edit.blade.php ENDPATH**/ ?>