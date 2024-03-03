<?php $__env->startSection('title',__($page_title)); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <h5><?php echo e($page_title); ?></h5>
                <div class="table-parent table-responsive">
                    <div class="text-end mb-4 me-3">
                        <a href="<?php echo e(route('user.ticket.create')); ?>">
                            <button class="btn-ticket">
                                <?php echo app('translator')->get('Create Ticket'); ?>
                            </button>
                        </a>
                    </div>
                    <table class="table table-striped" id="service-table">
                        <thead>
                        <tr>
                            <th scope="col"><?php echo app('translator')->get('Subject'); ?></th>
                            <th scope="col"><?php echo app('translator')->get('Status'); ?></th>
                            <th scope="col"><?php echo app('translator')->get('Last Reply'); ?></th>
                            <th scope="col"><?php echo app('translator')->get('Action'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td data-label="<?php echo app('translator')->get('Subject'); ?>">
                                                    <span
                                                        class="font-weight-bold"> [<?php echo e(trans('Ticket#').$ticket->ticket); ?>

                                                        ] <?php echo e($ticket->subject); ?> </span>
                                </td>
                                <td data-label="<?php echo app('translator')->get('Status'); ?>">
                                    <?php if($ticket->status == 0): ?>
                                        <span
                                            class="badge bg-success"><?php echo app('translator')->get('Open'); ?></span>
                                    <?php elseif($ticket->status == 1): ?>
                                        <span
                                            class="badge bg-primary"><?php echo app('translator')->get('Answered'); ?></span>
                                    <?php elseif($ticket->status == 2): ?>
                                        <span
                                            class="badge bg-warning"><?php echo app('translator')->get('Replied'); ?></span>
                                    <?php elseif($ticket->status == 3): ?>
                                        <span class="badge bg-dark"><?php echo app('translator')->get('Closed'); ?></span>
                                    <?php endif; ?>
                                </td>

                                <td data-label="<?php echo app('translator')->get('Last Reply'); ?>">
                                    <?php echo e(diffForHumans($ticket->last_reply)); ?>

                                </td>

                                <td data-label="<?php echo app('translator')->get('Action'); ?>">
                                    <a href="<?php echo e(route('user.ticket.view', $ticket->ticket)); ?>"
                                       class="btn btn-sm btn-detail"
                                       data-toggle="tooltip" title="" data-original-title="Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
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
                        <?php echo e($tickets->appends($_GET)->links($theme.'partials.pagination')); ?>

                    </ul>
                </nav>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/support/index.blade.php ENDPATH**/ ?>