<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Create Fiat Currency'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="media mb-4 justify-content-end">
                <a href="<?php echo e(route('admin.listFiat')); ?>" class="btn btn-sm  btn-primary mr-2">
                    <span><i class="fas fa-arrow-left"></i> <?php echo app('translator')->get('Back'); ?></span>
                </a>
            </div>

            <form action="<?php echo e(route('admin.storeFiat')); ?>" class="form-row justify-content-center" method="post"
                  enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="col-md-8">
                    <div class="row ">
                        <div class=" col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Name'); ?></label>
                                <input type="text" name="name" value="<?php echo e(old('name')); ?>"
                                       placeholder="<?php echo app('translator')->get('eg. American Dollar, Indian Rupee'); ?>" class="form-control" required>
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

                        <div class=" col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Code'); ?></label>
                                <input type="text" name="code" value="<?php echo e(old('code')); ?>"
                                       placeholder="<?php echo app('translator')->get('eg. USD, INR'); ?>" class="form-control" required>
                                <?php $__errorArgs = ['code'];
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


                        <div class=" col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Symbol'); ?></label>
                                <input type="text" name="symbol" value="<?php echo e(old('symbol')); ?>"
                                       placeholder="<?php echo app('translator')->get('eg. $, ₹'); ?>" class="form-control" required>
                                <?php $__errorArgs = ['symbol'];
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


                        <div class="form-group col-md-6">
                            <label><?php echo app('translator')->get('Rate'); ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?php echo app('translator')->get('1 USD'); ?><?php echo app('translator')->get(' = '); ?></span>
                                </div>
                                <input type="text" name="rate" value="<?php echo e(old('rate')); ?>" class="form-control"
                                       placeholder="0" required>

                                <div class="input-group-append">
                                    <span
                                        class="input-group-text currencyReserveSign"><?php echo e(config('basic.currency')); ?></span>
                                </div>
                            </div>
                            <?php $__errorArgs = ['rate'];
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


                        <div class="col-md-6">
                            <label for="name"> <?php echo app('translator')->get('Deposit Charge'); ?> </label>
                            <div class="input-group">
                                <input type="text" name="deposit_charge"
                                       class="form-control"
                                       value="<?php echo e(old('deposit_charge')); ?>">
                                <div class="input-group-append">
                                    <select class="form-control  mb-3" name="deposit_type"
                                            aria-label=".form-select-lg example">
                                        <option value="1"
                                                class="minMaxCurrency"><?php echo app('translator')->get(config('basic.currency')); ?></option>
                                        <option value="0">%</option>
                                    </select>
                                </div>
                                <?php $__errorArgs = ['deposit_charge'];
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

                        <div class="col-md-6">
                            <label for="name"> <?php echo app('translator')->get('Withdarw Charge'); ?> </label>
                            <div class="input-group">
                                <input type="text" name="withdraw_charge"
                                       class="form-control"
                                       value="<?php echo e(old('withdraw_charge')); ?>">
                                <div class="input-group-append">
                                    <select class="form-control  mb-3" name="withdraw_type"
                                            aria-label=".form-select-lg example">
                                        <option value="1"
                                                class="minMaxCurrency"><?php echo app('translator')->get(config('basic.currency')); ?></option>
                                        <option value="0">%</option>
                                    </select>
                                </div>
                                <?php $__errorArgs = ['withdraw_charge'];
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

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image"><?php echo app('translator')->get('Image'); ?></label>
                                <div class="image-input ">
                                    <label for="image-upload" id="image-label"><i
                                            class="fas fa-upload"></i></label>
                                    <input type="file" name="image" placeholder="<?php echo app('translator')->get('Choose image'); ?>"
                                           id="image" required>
                                    <img id="image_preview_container" class="preview-image"
                                         src="<?php echo e(getFile(config('location.category.path'))); ?>"
                                         alt="<?php echo app('translator')->get('preview image'); ?>">
                                </div>
                                <span
                                    class="text-secondary"><?php echo app('translator')->get('Image size'); ?> <?php echo e(config('location.currency.size')); ?> <?php echo app('translator')->get('px'); ?></span>
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

                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label><?php echo app('translator')->get('Status'); ?></label>
                                    <input data-toggle="toggle" id="status" data-onstyle="success"
                                           data-offstyle="info" data-on="Active" data-off="Deactive" data-width="100%"
                                           type="checkbox" checked name="status">
                                    <?php $__errorArgs = ['status'];
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
                        </div>
                    </div>

                    <button type="submit"
                            class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                                class="fas fa-save pr-2"></i> <?php echo app('translator')->get('Save'); ?></span></button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/summernote.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('js-lib'); ?>
    <script src="<?php echo e(asset('assets/admin/js/summernote.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>
    <script>
        "use strict";

        $(document).ready(function () {
            $('select[name=category_id]').select2({
                selectOnClose: true
            });
        });
    </script>
    <script>
        "use strict";

        $(document).on('keyup', 'input[name=code]', function (e) {
            $('.currencySign').text("1 " + $(this).val());
            $('.currencyReserveSign').text($(this).val());
            $('.minMaxCurrency').text($(this).val());
        })

        $(document).ready(function (e) {

            $("#generate").on('click', function () {
                var form = `<div class="col-md-12">
                <div class="form-group">
                    <div class="input-group">
                        <input name="field_name[]" class="form-control " type="text" value="" required placeholder="<?php echo e(trans('Field Name')); ?>">

                        <select name="type[]"  class="form-control  ">
                            <option value="text"><?php echo e(trans('Input Text')); ?></option>
                            <option value="textarea"><?php echo e(trans('Textarea')); ?></option>
                            <option value="file" class="d-none"><?php echo e(trans('File upload')); ?></option>
                        </select>

                        <select name="validation[]"  class="form-control  ">
                            <option value="required"><?php echo e(trans('Required')); ?></option>
                            <option value="nullable"><?php echo e(trans('Optional')); ?></option>
                        </select>

                        <span class="input-group-btn">
                            <button class="btn btn-danger delete_desc" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div> `;

                $('.addedField').append(form)
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.input-group').parent().remove();
            });

            $("#generate-specification").on('click', function () {
                var form = `<div class="col-md-12">
                <div class="form-group">
                    <div class="input-group">
                        <input name="field_specification[]" class="form-control " type="text" value="" required placeholder="<?php echo e(trans('Field Name')); ?>">

                        <select name="type_specification[]"  class="form-control">
                            <option value="text"><?php echo e(trans('Input Text')); ?></option>
                            <option value="textarea"><?php echo e(trans('Textarea')); ?></option>
                            <option value="file" class="d-none"><?php echo e(trans('File upload')); ?></option>
                        </select>

                        <select name="validation_specification[]"  class="form-control  ">
                            <option value="required"><?php echo e(trans('Required')); ?></option>
                            <option value="nullable"><?php echo e(trans('Optional')); ?></option>
                        </select>

                        <span class="input-group-btn">
                            <button class="btn btn-danger delete_desc" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div> `;

                $('.addedSpecification').append(form)
            });


            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.input-group').parent().remove();
            });

        });

        $('#image').on('change',function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#image_preview_container').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.summernote').summernote({
            height: 250,
            callbacks: {
                onBlurCodeview: function () {
                    let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable')
                        .val();
                    $(this).val(codeviewHtml);
                }
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/currency/fiat/create.blade.php ENDPATH**/ ?>