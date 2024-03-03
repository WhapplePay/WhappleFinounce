<!-- footer section -->
<footer class="footer-section">
    <div class="container">
        <div class="row gy-5 gy-lg-0">
            <div class="col-lg-3 col-md-6">
                <div class="footer-box">
                    <a class="navbar-brand" href="javascript:void(0)"> <img
                            src="<?php echo e(getFile(config('location.logoIcon.path') . 'logo.png')); ?>" alt=""/></a>
                    <?php if(isset($contactUs['contact-us'][0]) && $contact = $contactUs['contact-us'][0]): ?>
                        <p class="company-bio">
                            <?php echo app('translator')->get(strip_tags(@$contact->description->footer_short_details)); ?>
                        </p>
                    <?php endif; ?>
                    <?php if(isset($contentDetails['social'])): ?>
                        <div class="social-links">
                            <?php $__currentLoopData = $contentDetails['social']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(@$data->content->contentMedia->description->link); ?>">
                                    <i class="<?php echo e(@$data->content->contentMedia->description->icon); ?>"></i>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-box">
                    <h5><?php echo app('translator')->get('Quick Links'); ?></h5>
                    <ul>
                        <li><a href="<?php echo e(route('home')); ?>">- <?php echo app('translator')->get('Home'); ?></a></li>
                        <li><a href="<?php echo e(route('about')); ?>">- <?php echo app('translator')->get('About'); ?></a></li>
                        <li><a href="<?php echo e(route('blog')); ?>">- <?php echo app('translator')->get('Blog'); ?></a></li>
                        <li><a href="<?php echo e(route('contact')); ?>">- <?php echo app('translator')->get('Contact'); ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">

                <?php if(isset($contentDetails['support'])): ?>
                    <div class="footer-box">
                        <h5><?php echo app('translator')->get('Support'); ?></h5>
                        <ul>
                            <?php $__currentLoopData = $contentDetails['support']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="<?php echo e(route('getLink', [slug($data->description->title), $data->content_id])); ?>"><?php echo app('translator')->get($data->description->title); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-box">
                    <h5><?php echo app('translator')->get('Newsletter'); ?></h5>
                    <div class="news-letter">
                        <form action="<?php echo e(route('subscribe')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="email" name="email" placeholder="<?php echo app('translator')->get('Email Address'); ?>" class="form-control"/>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <button class="btn-custom"><?php echo app('translator')->get('subscribe now'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-flex copyright justify-content-start justify-content-sm-between">
            <div class="col-md-7">
              <span>
                     <?php echo app('translator')->get('Copyright'); ?> &copy; <?php echo e(date('Y')); ?> <?php echo app('translator')->get($basic->site_title); ?> <?php echo app('translator')->get('All Rights Reserved'); ?>
                  </span>
            </div>
            <div class="col-md-5 mt-3 mt-sm-0 text-sm-end text-start">
                <?php $__empty_1 = true; $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('language',$item->short_name)); ?>" class="language"><?php echo app('translator')->get($item->name); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/partials/footer.blade.php ENDPATH**/ ?>