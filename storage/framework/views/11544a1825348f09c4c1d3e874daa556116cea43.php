<?php $__env->startSection('title', trans($title)); ?>

<?php $__env->startSection('content'); ?>
    <!-- blog section  -->
    <?php if(isset($contentDetails['blog'])): ?>
        <section class="blog-page blog-details">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <?php $__empty_1 = true; $__currentLoopData = $contentDetails['blog']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="blog-box">
                                <div class="img-box">
                                    <img
                                        src="<?php echo e(getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image)); ?>"
                                        class="img-fluid" alt="..."/>
                                </div>
                                <div class="text-box">
                                    <div class="date-author">
                                        <span><i class="fal fa-clock"></i> <?php echo app('translator')->get(dateTime($item->created_at)); ?></span>
                                    </div>
                                    <h5 class="title"><?php echo app('translator')->get(optional($item->description)->title); ?></h5>
                                    <p>
                                        <?php echo app('translator')->get(Str::limit(optional($item->description)->description,256)); ?>
                                    </p>
                                    <a href="<?php echo e(route('blogDetails', [slug(@$item->description->title), $item->content_id])); ?>"
                                       class="btn-custom mt-3"><?php echo app('translator')->get('Read more'); ?></a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-4">
                        <div class="side-bar">
                            <div class="side-box">
                                <form  action="<?php echo e(route('blog')); ?>" method="get">
                                    <h5><?php echo app('translator')->get('Search'); ?></h5>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" value="<?php echo e(request()->search); ?>" placeholder="<?php echo app('translator')->get('search'); ?>"/>
                                        <button type="submit"><i class="fal fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="side-box">
                                <h5><?php echo app('translator')->get('Recent Posts'); ?></h5>
                                <?php $__empty_1 = true; $__currentLoopData = $contentDetails['blog']->sortBy('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="blog-box">
                                        <div class="img-box">
                                            <img class="img-fluid" src="<?php echo e(getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image)); ?>" alt="..."/>
                                        </div>
                                        <div class="text-box">
                                            <a href="<?php echo e(route('blogDetails', [slug(@$item->description->title), $item->content_id])); ?>" class="title"
                                            ><?php echo app('translator')->get(optional($item->description)->title); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/blog.blade.php ENDPATH**/ ?>