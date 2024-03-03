<style>
    #page-banner {
        background-image: linear-gradient(90deg, rgba(7, 11, 40, 0.65) 0%, rgba(7, 11, 40, 0.65) 100%), url(<?php echo e(getFile(config('location.logo.path').'banner.jpg')); ?>);
    }
</style>
<?php if(!request()->routeIs('home')): ?>
    <!-- banner section -->
    <section class="banner-section">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <h3><?php echo $__env->yieldContent('title'); ?></h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo app('translator')->get('Home'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $__env->yieldContent('title'); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/partials/banner.blade.php ENDPATH**/ ?>