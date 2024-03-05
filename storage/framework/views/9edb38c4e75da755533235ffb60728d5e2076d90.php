<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Edit Crypto Currency'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="media mb-4 justify-content-end">
                <a href="<?php echo e(route('admin.listCrypto')); ?>" class="btn btn-sm  btn-primary mr-2">
                    <span><i class="fas fa-arrow-left"></i> <?php echo app('translator')->get('Back'); ?></span>
                </a>
            </div>

            <form action="<?php echo e(route('admin.updateCrypto',$crypto->id)); ?>" class="form-row justify-content-center"
                  method="post"
                  enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="col-md-8">
                    <div class="row ">
                        <div class=" col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Name'); ?></label>
                                <input type="text" name="name" value="<?php echo e($crypto->name); ?>"
                                       class="form-control" required>
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
                                <input type="text" name="code" value="<?php echo e($crypto->code); ?>"
                                       class="form-control" required>
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
                                <input type="text" name="symbol" value="<?php echo e($crypto->symbol); ?>"
                                       class="form-control" required>
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
                                    <span class="input-group-text currencySign"> 1 <?php echo e($crypto->code); ?></span>
                                </div>
                                <input type="text" name="rate" value="<?php echo e($crypto->rate+0); ?>" class="form-control"
                                       placeholder="0" required>
                                <div class="input-group-append">
                                    <span class="input-group-text"><?php echo app('translator')->get('USD'); ?></span>
                                </div>
                            </div>
                            <?php $__errorArgs = ['buy_rate'];
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
                                       value="<?php echo e($crypto->deposit_charge); ?>">
                                <div class="input-group-append">
                                    <select class="form-control  mb-3" name="deposit_type"
                                            aria-label=".form-select-lg example">
                                        <option value="1" <?php if($crypto->deposit_type==0): ?> selected
                                                <?php endif; ?> class="minMaxCurrency"><?php echo app('translator')->get($crypto->code); ?></option>
                                        <option value="0" <?php if($crypto->deposit_type==1): ?> selected <?php endif; ?>>%</option>
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
                            <label for="name"> <?php echo app('translator')->get('Withdraw Charge'); ?> </label>
                            <div class="input-group">
                                <input type="text" name="withdraw_charge"
                                       class="form-control"
                                       value="<?php echo e($crypto->withdraw_charge); ?>">
                                <div class="input-group-append">
                                    <select class="form-control  mb-3" name="withdraw_type"
                                            aria-label=".form-select-lg example">
                                        <option value="1" <?php if($crypto->withdraw_type==0): ?> selected
                                                <?php endif; ?> class="minMaxCurrency"><?php echo app('translator')->get($crypto->code); ?></option>
                                        <option value="0" <?php if($crypto->withdraw_type==1): ?> selected <?php endif; ?>>%</option>
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
                                           id="image">
                                    <img id="image_preview_container" class="preview-image"
                                         src="<?php echo e(getFile(config('location.currency.path').$crypto->image)); ?>"
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
                                           type="checkbox" <?php if($crypto->status == 1): ?> checked <?php endif; ?>  name="status">
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
        $(document).on('keyup', 'input[name=code]', function (e) {
            $('.currencySign').text("1 " + $(this).val());
            $('.currencyReserveSign').text($(this).val());
            $('.minMaxCurrency').text($(this).val());
        })

        $('#image').on('change',function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#image_preview_container').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/currency/crypto/edit.blade.php ENDPATH**/ ?>