<!-- navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>"> <img
                src="<?php echo e(getFile(config('location.logoIcon.path') . 'logo.png')); ?>" alt="..."/>
        </a>
        <button class="sidebar-toggler" onclick="toggleSideMenu()">
            <i class="far fa-bars"></i>
        </button>
        <!-- navbar text -->
        <span class="navbar-text">
            <!-- notification panel -->
            <?php echo $__env->make($theme.'partials.pushNotify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="user-panel">
               <span class="profile">
                  <img src="<?php echo e(getFile(config('location.user.path').auth()->user()->image)); ?>" class="img-fluid"
                       alt="..."/>
               </span>
               <ul class="user-dropdown">
                  <li>
                     <a href="<?php echo e(route('user.profile')); ?>"> <i class="fal fa-user"></i> <?php echo app('translator')->get('Profile'); ?> </a>
                  </li>
                  <li>
                     <a href="<?php echo e(route('user.password')); ?>"> <i class="fal fa-key"></i> <?php echo app('translator')->get('Change Password'); ?> </a>
                  </li>
                  <li>
                     <a href="<?php echo e(route('logout')); ?>"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i
                             class="fal fa-sign-out-alt"></i> <?php echo app('translator')->get('Sign Out'); ?> </a>
                      <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                          <?php echo csrf_field(); ?>
                      </form>
                  </li>
               </ul>
            </div>
        </span>
    </div>
</nav>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/partials/userNav.blade.php ENDPATH**/ ?>