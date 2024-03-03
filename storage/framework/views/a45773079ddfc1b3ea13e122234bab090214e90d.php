<!-- feature section -->
<?php if(isset($contentDetails['feature'])): ?>
    <section class="feature-section">
        <div class="container">
            <?php if(isset($templates['feature'][0]) && $feature = $templates['feature'][0]): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5><?php echo app('translator')->get(optional($feature->description)->title); ?></h5>
                            <h2><?php echo app('translator')->get(optional($feature->description)->sub_title); ?></h2>
                            <p class="mx-auto">
                                <?php echo app('translator')->get(optional($feature->description)->short_description); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row g-4">
                <?php $__empty_1 = true; $__currentLoopData = $contentDetails['feature']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-6 col-lg-4">
                        <div
                            class="feature-box"
                            data-aos="fade-up"
                            data-aos-duration="1000"
                            data-aos-anchor-placement="center-bottom">
                            <div class="icon-box">
                                <img src="<?php echo e(getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image)); ?>" alt="..."/>
                            </div>
                            <h5><?php echo app('translator')->get(optional($item->description)->title); ?></h5>
                            <p>
                                <?php echo app('translator')->get(optional($item->description)->information); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/sections/feature.blade.php ENDPATH**/ ?>