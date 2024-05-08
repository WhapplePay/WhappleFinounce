<?php $__env->startSection('title','Reset Password'); ?>
<?php $__env->startSection('content'); ?>
    <section class="login-section">
        <div class="container h-100">
            <div class="row h-100 justify-content-center">
                <div class="col-lg-6">
                    <div class="form-wrapper d-flex align-items-center h-100">
                        <form action="<?php echo e(route('password.email')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row g-4">
                                <div class="col-12">
                                    <?php if(session('status')): ?>
                                        <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                                            <?php echo e(trans(session('status'))); ?>

                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close">
                                                <span aria-hidden="true"></span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12">
                                    <h5><?php echo app('translator')->get('Recover Password'); ?></h5>
                                </div>
                                <div class="input-box col-12">
                                    <input type="text" name="email" value="<?php echo e(old('email')); ?>" class="form-control"
                                           placeholder="<?php echo app('translator')->get('Email address'); ?>"/>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger  mt-1"><?php echo e(trans($message)); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-12">
                                    <button type="submit"
                                            class="btn-custom w-100"><?php echo app('translator')->get('Send Password Reset Link'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make($theme.'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/auth/passwords/email.blade.php ENDPATH**/ ?>