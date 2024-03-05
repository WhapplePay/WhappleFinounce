<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Feedback'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="table-parent table-responsive mt-5">
            <table class="table table-striped" id="service-table">
                <thead>
                <tr>
                    <th><?php echo app('translator')->get('SL No.'); ?></th>
                    <th><?php echo app('translator')->get('Given By'); ?></th>
                    <th><?php echo app('translator')->get('Feedback'); ?></th>
                    <th><?php echo app('translator')->get('Given At'); ?></th>
                    <th><?php echo app('translator')->get('Action'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="<?php echo app('translator')->get('SL No.'); ?>"><?php echo e(++$key); ?></td>
                        <td data-label="<?php echo app('translator')->get('Seller'); ?>">
                            <a href="<?php echo e(route('user.profile.page',$item->reviewer_id)); ?>">
                                <div class="d-lg-flex d-block align-items-center">
                                    <div class="me-3"><img
                                            src="<?php echo e(getFile(config('location.user.path').optional($item->reviewer)->image)); ?>"
                                            alt="user" class="rounded-circle" width="45"
                                            height="45"></div>
                                    <div class="">
                                        <h6 class="text-white mb-0 text-lowercase"><?php echo app('translator')->get(optional($item->reviewer)->username); ?></h6>
                                        <span
                                            class="text-muted font-14 text-lowercase"><?php echo e(optional($item->reviewer)->email); ?></span>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td data-label="<?php echo app('translator')->get('Feedback'); ?>">
                            <?php if($item->position == 'like'): ?>
                                <i class="far fa-thumbs-up"></i>
                            <?php elseif($item->position == 'dislike'): ?>
                                <i class="far fa-thumbs-down"></i>
                            <?php endif; ?>
                        </td>
                        <td data-label="<?php echo app('translator')->get('Given At'); ?>"><span><?php echo e(dateTime($item->created_at,'d M, Y h:i A')); ?></span>
                        </td>
                        <td data-label="<?php echo app('translator')->get('Action'); ?>" class="action">
                            <button class="btn-custom details" data-resource="<?php echo e($item); ?>"
                                    data-bs-target="#detailsModal"
                                    data-bs-toggle="modal">
                                <?php echo app('translator')->get('Details'); ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="100%">
                            <div class="no-data-message">
                                <span class="icon-wrapper">
                                    <span class="file-icon">
                                        <i class="fas fa-file-times"></i>
                                    </span>
                                </span>
                                <p class="message"><?php echo app('translator')->get('No data found'); ?></p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php echo e($ads->appends($_GET)->links($theme.'partials.pagination')); ?>

            </ul>
        </nav>
    </div>

    <!--Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel"><?php echo app('translator')->get('Feedback'); ?></h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form>
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="input-box">
                            <label
                                for="exampleFormControlTextarea1"
                                class="form-label"><?php echo app('translator')->get('message'); ?></label>
                            <textarea
                                class="form-control color-adjust textMessage"
                                id="exampleFormControlTextarea1"
                                rows="5"
                                name="feedback"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        'use script'
        $(document).on('click', '.details', function (e) {
            var obj = $(this).data('resource');
            $('.textMessage').val(obj.feedback);
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/feedback/index.blade.php ENDPATH**/ ?>