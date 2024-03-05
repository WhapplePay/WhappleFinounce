<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Api Setting'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="text-dark"><?php echo app('translator')->get('CoinPayment Setting'); ?></h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.api-setting.update')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class=" col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Public Key'); ?> <sup class="text-danger font-16">*</sup></label>
                                    <input type="text" name="public_key" value="<?php echo e($configure->public_key??'null'); ?>"
                                           placeholder="" class="form-control">
                                    <?php $__errorArgs = ['public_key'];
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
                                    <label><?php echo app('translator')->get('Private Key'); ?> <sup class="text-danger font-16">*</sup></label>
                                    <input type="text" name="private_key" value="<?php echo e($configure->private_key??'null'); ?>"
                                           placeholder="" class="form-control">
                                    <?php $__errorArgs = ['private_key'];
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
                        <div class="row">
                            <div class=" col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Merchant ID'); ?> <sup class="text-danger font-16">*</sup></label>
                                    <input type="text" name="merchant_id" value="<?php echo e($configure->merchant_id??'null'); ?>"
                                           placeholder="" class="form-control">
                                    <?php $__errorArgs = ['merchant_id'];
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
                        <button type="submit"
                                class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                                    class="fas fa-save pr-2"></i> <?php echo app('translator')->get('Update'); ?></span></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div
                    class="card-header bg-primary text-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold"><?php echo app('translator')->get('Instructions'); ?></h6>
                </div>
                <div class="card-body">
                    <?php echo app('translator')->get('Using CoinPayments, merchants can accept crypto payments via APIs and a mobile POS system, and choose to either hold the funds in cryptocurrency or have them automatically converted into fiat currency and sent into a company bank account
                    <br><br>
                    Get your free API keys'); ?>
                    <a href="https://www.coinpayments.net/register" target="_blank"><?php echo app('translator')->get('Create an account'); ?>
                        <i class="fas fa-external-link-alt"></i></a>
                    <?php echo app('translator')->get(', then create a account.
                    Go to the panel page for public key, Private key and Merchant ID.'); ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
    <script>
        'use strict'

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
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/api/index.blade.php ENDPATH**/ ?>