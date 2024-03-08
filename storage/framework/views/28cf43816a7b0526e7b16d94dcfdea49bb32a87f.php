<?php $__env->startSection('title',trans('Blog Details')); ?>

<?php $__env->startSection('content'); ?>
    <!-- blog section  -->
    <section class="blog-page blog-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-box">
                        <div class="img-box">
                            <img src="<?php echo e($singleItem['image']); ?>" class="img-fluid" alt="<?php echo e($singleItem['title']); ?>"/>
                        </div>
                        <div class="text-box">
                            <div class="date-author">
                                <span><i class="fal fa-clock"></i> <?php echo e($singleItem['date']); ?></span>
                            </div>
                            <h5 class="title"><?php echo app('translator')->get($singleItem['title']); ?></h5>
                            <p>
                                <?php echo app('translator')->get($singleItem['description']); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php if(isset($popularContentDetails['blog'])): ?>
                    <div class="col-lg-4">
                        <div class="side-bar">
                            <div class="side-box">
                                <h5><?php echo app('translator')->get('Related Posts'); ?></h5>
                                <?php $__currentLoopData = $popularContentDetails['blog']->sortDesc(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="blog-box">
                                        <div class="img-box">
                                            <img class="img-fluid" src="<?php echo e(getFile(config('location.content.path') . optional($data->content)->contentMedia->description->image)); ?>" alt=""/>
                                        </div>
                                        <div class="text-box">
                                            <a href="<?php echo e(route('blogDetails', [slug(optional($data->description)->title), $data->content_id])); ?>" class="title"
                                            ><?php echo app('translator')->get($data->description->title); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/blogDetails.blade.php ENDPATH**/ ?>