<!-- faq section -->
<?php if(isset($contentDetails['faq'])): ?>
    <section class="faq-section">
        <div class="container">
            <?php if(isset($templates['faq'][0]) && $faq = $templates['faq'][0]): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5><?php echo app('translator')->get(optional($faq->description)->title); ?></h5>
                            <h2><?php echo app('translator')->get(optional($faq->description)->sub_title); ?></h2>
                            <p class="mx-auto">
                                <?php echo app('translator')->get(optional($faq->description)->short_description); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-lg-6">
                    <div
                        class="img-box"
                        data-aos="fade-right"
                        data-aos-duration="1000"
                        data-aos-anchor-placement="center-bottom">
                        <img src="<?php echo e(getFile(config('location.content.path').@$faq->templateMedia()->image)); ?>"
                             class="img-fluid" alt="..."/>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div
                        class="accordion"
                        id="accordionExample"
                        data-aos="fade-left"
                        data-aos-duration="1000"
                        data-aos-anchor-placement="center-bottom">
                        <?php $__empty_1 = true; $__currentLoopData = $contentDetails['faq']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="accordion-item">
                                <h5 class="accordion-header" id="heading<?php echo e($key); ?>">
                                    <button
                                        class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse<?php echo e($key); ?>"
                                        aria-expanded="false"
                                        aria-controls="collapse<?php echo e($key); ?>">
                                        <?php echo app('translator')->get(@$item->description->title); ?>
                                    </button>
                                </h5>
                                <div
                                    id="collapse<?php echo e($key); ?>"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <?php echo app('translator')->get(optional($item->description)->description); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/sections/faq.blade.php ENDPATH**/ ?>