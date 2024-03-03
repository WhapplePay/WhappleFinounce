<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Settings'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- main -->
    <div class="container-fluid">
        <div class="main row g-4">
            <div class="company-setting-wrapper col-12">
                <div class="row">
                    <div <?php if(request()->routeIs('user.list.setting')): ?> class="col-lg-12" <?php else: ?> class="col-lg-8" <?php endif; ?>>
                        <div class="settings-wrapper">
                            <h5 class="mb-3"><?php echo app('translator')->get('Settings'); ?></h5>
                            <a href="<?php echo e(route('user.password')); ?>">
                                <div class="box <?php echo e(menuActive(['user.password','user.updatePassword'])); ?>">
                                    <h5><?php echo app('translator')->get('Password Setting'); ?></h5>
                                    <p><?php echo app('translator')->get('This is password setting where you can change password click to continue.'); ?></p>
                                </div>
                            </a>
                            <a href="<?php echo e(route('user.identityVerify')); ?>">
                                <div class="box <?php echo e(menuActive(['user.identityVerify','user.verificationSubmit'])); ?>">
                                    <h5><?php echo app('translator')->get('Identity Verification'); ?></h5>
                                    <p><?php echo app('translator')->get('This is identity verification setting where you can verified your identity click to continue.'); ?></p>
                                </div>
                            </a>
                            <a href="<?php echo e(route('user.twostep.security')); ?>">
                                <div
                                    class="box <?php echo e(menuActive(['user.twostep.security'])); ?>">
                                    <h5><?php echo app('translator')->get('2 FA Security'); ?></h5>
                                    <p><?php echo app('translator')->get('Two Factor Security prevent from unauthorized login action click to continue.'); ?></p>
                                </div>
                            </a>
                            <a href="<?php echo e(route('user.list.setting.notify')); ?>">
                                <div
                                    class="box <?php echo e(menuActive(['user.list.setting.notify','user.update.setting.notify'])); ?>">
                                    <h5><?php echo app('translator')->get('Push Notify Setting'); ?></h5>
                                    <p><?php echo app('translator')->get('This is push notification setting where you can manage your notification action click to continue.'); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php echo $__env->yieldContent('dynamic'); ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/setting/index.blade.php ENDPATH**/ ?>