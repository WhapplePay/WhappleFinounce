<!-- navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>"> <img
                src="<?php echo e(getFile(config('location.logoIcon.path') . 'logo.png')); ?>" alt="..."/></a>
        <button
            class="navbar-toggler p-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="far fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(menuActive('home')); ?>" href="<?php echo e(route('home')); ?>"><?php echo app('translator')->get('Home'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(menuActive('about')); ?>" href="<?php echo e(route('about')); ?>"><?php echo app('translator')->get('About'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(menuActive('buy')); ?>" href="<?php echo e(route('buy')); ?>"><?php echo app('translator')->get('Buy'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(menuActive('sell')); ?>" href="<?php echo e(route('sell')); ?>"><?php echo app('translator')->get('Sell'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(menuActive('blog')); ?>" href="<?php echo e(route('blog')); ?>"><?php echo app('translator')->get('Blogs'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(menuActive('contact')); ?>" href="<?php echo e(route('contact')); ?>"><?php echo app('translator')->get('Contact'); ?></a>
                </li>
            </ul>
        </div>
        <!-- navbar text -->
        <span class="navbar-text">
            <?php if(auth()->guard()->check()): ?>
                <a class="user-panel" href="<?php echo e(route('user.home')); ?>">
                  <i class="fal fa-user-circle"></i>
               </a>
                <!-- notification panel -->
                <?php echo $__env->make($theme.'partials.pushNotify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('login')); ?>" class="btn-custom"><?php echo app('translator')->get('Sign In'); ?></a>
            <?php endif; ?>
            </span>
    </div>
</nav>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/partials/nav.blade.php ENDPATH**/ ?>