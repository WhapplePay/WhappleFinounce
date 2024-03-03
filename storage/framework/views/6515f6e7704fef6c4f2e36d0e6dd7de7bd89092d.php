<!-- about section -->
<?php if(isset($templates['about-us'][0]) && $about_us = $templates['about-us'][0]): ?>
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div
                        class="img-box"
                        data-aos="fade-right"
                        data-aos-duration="1000"
                        data-aos-anchor-placement="center-bottom">
                        <img src="<?php echo e(getFile(config('location.content.path').@$about_us->templateMedia()->image)); ?>"
                             class="img-fluid rounded-circle" alt="..."/>
                        <a class="play-vdo" href="<?php echo e(@$about_us->templateMedia()->video_link); ?>" data-fancybox="video">
                            <i class="fas fa-play"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div
                        class="text-box"
                        data-aos="fade-left"
                        data-aos-duration="1000"
                        data-aos-anchor-placement="center-bottom">
                        <div class="header-text mb-5">
                            <h5><?php echo app('translator')->get(optional($about_us->description)->title); ?></h5>
                            <h2><?php echo app('translator')->get(optional($about_us->description)->sub_title); ?></h2>
                        </div>
                        <p>
                            <?php echo app('translator')->get(optional($about_us->description)->short_description); ?>
                        </p>
                        <ul>
                            <?php if(isset($contentDetails['about-us'])): ?>
                                <?php $__empty_1 = true; $__currentLoopData = $contentDetails['about-us']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <li>
                                        <i class="fal fa-check"></i>
                                        <?php echo app('translator')->get(optional($item->description)->short_description); ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                        <a href="<?php echo e(@$about_us->templateMedia()->button_link); ?>">
                            <button
                                class="btn-custom mt-4"><?php echo app('translator')->get(optional($about_us->description)->button_name); ?></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/sections/about-us.blade.php ENDPATH**/ ?>