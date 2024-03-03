<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php echo $__env->make('partials.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/bootstrap.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/style.css')); ?>"/>
</head>
<body>
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html>

<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/layouts/error.blade.php ENDPATH**/ ?>