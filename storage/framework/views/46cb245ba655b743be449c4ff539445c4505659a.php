<!-- home section -->
<?php if(isset($templates['hero'][0]) && $hero = $templates['hero'][0]): ?>
    <section class="home-section">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-lg-6">
                    <div class="text-box">
                        <h5><?php echo app('translator')->get(optional($hero->description)->heading); ?></h5>
                        <h3><?php echo app('translator')->get(optional($hero->description)->sub_heading); ?></h3>
                        <h1><?php echo app('translator')->get(optional($hero->description)->title); ?></h1>
                        <p>
                            <?php echo app('translator')->get(optional($hero->description)->short_description); ?>
                        </p>
                        <a href="<?php echo e($hero->templateMedia()->button_link); ?>">
                            <button class="btn-custom mt-4"><?php echo app('translator')->get(optional($hero->description)->button_name); ?></button>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="img-box">
                        <img src="<?php echo e(getFile(config('location.content.path').@$hero->templateMedia()->image)); ?>" class="img-fluid rounded-circle" alt="..."/>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/sections/homeBanner.blade.php ENDPATH**/ ?>