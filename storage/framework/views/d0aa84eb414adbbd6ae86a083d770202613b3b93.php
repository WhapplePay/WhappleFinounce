<?php $__env->startSection('title','405'); ?>
<?php $__env->startSection('content'); ?>
    <section class="not-found">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col">
                    <div class="text-box text-center">
                        <img src="<?php echo e(asset($themeTrue.'/images/404.svg')); ?>" alt="..." />
                        <h1><?php echo e(trans('405')); ?></h1>
                        <p><?php echo e(trans("Method Not Allowed")); ?></p>
                        <a href="<?php echo e(url('/')); ?>"><?php echo app('translator')->get('Back To Home'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/errors/405.blade.php ENDPATH**/ ?>