<!-- blog section -->
<?php if(isset($contentDetails['blog'])): ?>
    <section class="blog-section">
        <div class="container">
            <?php if(isset($templates['blog'][0]) && $blog = $templates['blog'][0]): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5><?php echo app('translator')->get(optional($blog->description)->title); ?></h5>
                            <h2><?php echo app('translator')->get(optional($blog->description)->sub_title); ?></h2>
                            <p class="mx-auto">
                                <?php echo app('translator')->get(optional($blog->description)->short_description); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <?php $__empty_1 = true; $__currentLoopData = $contentDetails['blog']->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-lg-4 col-md-6">
                        <div
                            class="blog-box"
                            data-aos="fade-right"
                            data-aos-duration="1000"
                            data-aos-anchor-placement="center-bottom">
                            <div class="img-box">
                                <img
                                    src="<?php echo e(getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image)); ?>"
                                    class="img-fluid" alt="..."/>
                            </div>
                            <div class="text-box">
                                <div class="date-author">
                                    <span><i class="fal fa-clock"></i><?php echo app('translator')->get(dateTime($item->created_at)); ?></span>
                                </div>
                                <a href="<?php echo e(route('blogDetails', [slug(optional($item->description)->title), $item->content_id])); ?>"
                                   class="title"><?php echo app('translator')->get(optional($item->description)->title); ?></a>
                                <p> <?php echo app('translator')->get(Str::limit($item->description->description,62)); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/sections/blog.blade.php ENDPATH**/ ?>