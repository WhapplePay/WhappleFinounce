<?php $__env->startSection('dynamic'); ?>
    <div class="col-lg-4">
        <div class="side-bar">
            <!-- edit profile section -->
            <div class="identity-confirmation">
                <form action="<?php echo e(route('user.updatePassword')); ?>" class="mt-0" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="row g-4">
                        <div class="col-12">
                            <h4><?php echo app('translator')->get('Update Password'); ?></h4>
                        </div>
                        <div class="input-box col-12">
                            <label for=""><?php echo app('translator')->get('Current Password'); ?></label>
                            <input class="form-control" id="password" type="password"
                                   name="current_password"
                                   autocomplete="off"/>
                            <?php if($errors->has('current_password')): ?>
                                <div
                                    class="error text-danger"><?php echo app('translator')->get($errors->first('current_password')); ?> </div>
                            <?php endif; ?>
                        </div>
                        <div class="input-box col-12">
                            <label for=""><?php echo app('translator')->get('New Password'); ?></label>
                            <input class="form-control" id="password" type="password" name="password"
                                   autocomplete="off"/>
                            <?php if($errors->has('password')): ?>
                                <div
                                    class="error text-danger"><?php echo app('translator')->get($errors->first('password')); ?> </div>
                            <?php endif; ?>
                        </div>
                        <div class="input-box col-12">
                            <label for=""><?php echo app('translator')->get('Confirm Password'); ?></label>
                            <input class="form-control" id="password_confirmation" type="password"
                                   name="password_confirmation"
                                   autocomplete="off"/>
                            <?php if($errors->has('password_confirmation')): ?>
                                <div
                                    class="error text-danger"><?php echo app('translator')->get($errors->first('password_confirmation')); ?> </div>
                            <?php endif; ?>
                        </div>
                        <div class="input-box col-12">
                            <button type="submit" class="btn-custom"><?php echo app('translator')->get('Update Password'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'user.setting.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/profile/password.blade.php ENDPATH**/ ?>