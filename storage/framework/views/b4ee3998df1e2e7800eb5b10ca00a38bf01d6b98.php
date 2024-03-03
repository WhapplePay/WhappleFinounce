<?php if(session()->has('success')): ?>
    <script>
        Notiflix.Notify.Success("<?php echo app('translator')->get(session('success')); ?>");
    </script>
<?php endif; ?>

<?php if(session()->has('error')): ?>
    <script>
        Notiflix.Notify.Failure("<?php echo app('translator')->get(session('error')); ?>");
    </script>
<?php endif; ?>

<?php if(session()->has('warning')): ?>
    <script>
        Notiflix.Notify.Warning("<?php echo app('translator')->get(session('warning')); ?>");
    </script>
<?php endif; ?>


<script>
    var root = document.querySelector(':root');
    root.style.setProperty('--primary', '<?php echo e(config('basic.base_color')??'#d09f06'); ?>');
    root.style.setProperty('--secondary', '<?php echo e(config('basic.secondary_color')??'#e1651a'); ?>');
</script>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/partials/notification.blade.php ENDPATH**/ ?>