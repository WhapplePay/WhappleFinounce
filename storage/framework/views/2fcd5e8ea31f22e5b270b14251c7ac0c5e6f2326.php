<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


    <?php echo $__env->make('partials.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/bootstrap.min.css')); ?>"/>
    <?php echo $__env->yieldPushContent('css-lib'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/animate.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/owl.carousel.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/owl.theme.default.min.css')); ?>"/>

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/aos.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/fancybox.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/select2.min.css')); ?>"/>

    <script src="<?php echo e(asset($themeTrue . 'js/fontawesomepro.js')); ?>"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/style.css')); ?>"/>


    <?php echo $__env->yieldPushContent('style'); ?>

</head>

<body>


<?php echo $__env->make($theme.'partials.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make($theme.'partials.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make($theme.'partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



<?php echo $__env->yieldPushContent('extra-content'); ?>


<script src="<?php echo e(asset($themeTrue . 'js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/masonry.pkgd.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/jquery.min.js')); ?>"></script>


<script src="<?php echo e(asset($themeTrue . 'js/owl.carousel.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/select2.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/fancybox.umd.js')); ?>"></script>

<?php echo $__env->yieldPushContent('extra-js'); ?>

<script src="<?php echo e(asset($themeTrue . 'js/jquery.waypoints.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/jquery.counterup.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/aos.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/socialSharing.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/script.js')); ?>"></script>


<script src="<?php echo e(asset('assets/global/js/pusher.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/vue.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/axios.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/notiflix-aio-2.7.0.min.js')); ?>"></script>


<?php echo $__env->yieldPushContent('script'); ?>


<?php echo $__env->make($theme.'partials.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    $(document).ready(function () {
        $(".language").find("select").on('change',function () {
            window.location.href = "<?php echo e(route('language')); ?>/" + $(this).val()
        })
    })
</script>

</body>
</html>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/layouts/app.blade.php ENDPATH**/ ?>