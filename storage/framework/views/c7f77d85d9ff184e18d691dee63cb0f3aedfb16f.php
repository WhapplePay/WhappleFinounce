<!-- testimonial section -->
<?php if(isset($contentDetails['testimonial'])): ?>
    <section class="testimonial-section">
        <div class="container">
            <?php if(isset($templates['testimonial'][0]) && $testimonial = $templates['testimonial'][0]): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5><?php echo app('translator')->get(optional($testimonial->description)->title); ?></h5>
                            <h2><?php echo app('translator')->get(optional($testimonial->description)->sub_title); ?></h2>
                            <p class="mx-auto">
                                <?php echo app('translator')->get(optional($testimonial->description)->short_description); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="testimonials owl-carousel">
                        <?php $__empty_1 = true; $__currentLoopData = $contentDetails['testimonial']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div
                                class="review-box"
                                data-aos="fade-up"
                                data-aos-duration="1000"
                                data-aos-anchor-placement="center-bottom">
                                <div class="top">
                                    <img
                                        src="<?php echo e(getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image)); ?>"
                                        alt="..."/>
                                    <div>
                                        <h5><?php echo app('translator')->get(optional($item->description)->name); ?></h5>
                                        <span class="title"><?php echo app('translator')->get(optional($item->description)->designation); ?></span>
                                        <span class="bar"></span>
                                        <span>
                                             <?php if(optional($item->description)->rating): ?>
                                                <?php for($i=0; $i<$item->description->rating; $i++): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php endfor; ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                                <p>
                                    <?php echo app('translator')->get(optional($item->description)->description); ?>
                                </p>
                                <img src="<?php echo e(asset($themeTrue.'/images/icon/right-quote.png')); ?>" alt="" class="quote"/>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/sections/testimonial.blade.php ENDPATH**/ ?>