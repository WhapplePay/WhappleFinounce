<?php $__env->startSection('dynamic'); ?>
    <div class="col-lg-4">
        <div class="side-bar">
            <!-- edit profile section -->
            <div class="identity-confirmation">
                <form role="form" method="POST" action="<?php echo e(route('user.update.setting.notify')); ?>"
                      enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('put'); ?>
                    <div class="row g-4">
                        <div class="table-parent table-responsive mt-5">
                            <table class="table table-striped" id="service-table">
                                <thead>
                                <tr>
                                    <th colspan="2"><?php echo app('translator')->get('Notification List'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td data-label="<?php echo app('translator')->get('Notification List'); ?>">
                                            <?php echo e($item->name); ?>

                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="flex<?php echo e($key); ?>"
                                                       name="access[]"
                                                       value="<?php echo e($item->template_key); ?>"
                                                       <?php if(in_array($item->template_key, auth()->user()->notify_active_template??[])): ?> checked
                                                       <?php endif; ?>
                                                       role="switch">
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn-custom"><?php echo app('translator')->get('Save Changes'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'user.setting.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/setting/notifyTemplate.blade.php ENDPATH**/ ?>