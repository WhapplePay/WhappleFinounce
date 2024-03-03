<?php $__env->startSection('title',trans('Your Wallet')); ?>
<?php $__env->startSection('content'); ?>

    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <h4><?php echo app('translator')->get('My Wallets'); ?></h4>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-xl-3 col-sm-6">
                    <div class="custom-card mb-4">
                        <div class="card-content <?php echo e(($cryptId == $item->crypto_currency_id)?'border-active':''); ?>">
                            <div class="card-top">
                                <div class="card_header"><?php echo e(optional($item->crypto)->name); ?></div>
                                <div class="dropdown">
                                    <a class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fal fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item text-capitalize deposit btnDeposit"
                                               href="javascript:void(0)"
                                               data-resource="<?php echo e($item); ?>"
                                               data-bs-target="#depositModal"
                                               data-bs-toggle="modal"><?php echo app('translator')->get('generate address'); ?></a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item text-capitalize withdraw btnWithdraw"
                                               href="javascript:void(0)"
                                               title="withdraw"
                                               data-resource="<?php echo e($item); ?>"
                                               data-bs-target="#withdrawModal"
                                               data-bs-toggle="modal"><?php echo app('translator')->get("withdraw"); ?></a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item text-capitalize"
                                               href="<?php echo e(route('user.transaction',optional($item->crypto)->code)); ?>"
                                               title="withdraw"><?php echo app('translator')->get("transaction"); ?></a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-capitalize"
                                               href="<?php echo e(route('user.wallet.list',$item->crypto_currency_id)); ?>"
                                               title="wallet address"><?php echo app('translator')->get("wallet address"); ?></a>
                                        </li>

                                    </ul>
                                </div>

                            </div>
                            <div class="card-bottom w-75">
                                <span
                                    class="flex-wrap "><?php echo e(rtrim(sprintf('%.8f',floatval($item->balance)),'0')); ?> <?php echo e(optional($item->crypto)->code); ?></span>
                            </div>
                            <div class="coin-thum">
                                <img
                                    src="<?php echo e(getFile(config('location.currency.path').optional($item->crypto)->image)); ?>"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php endif; ?>
        </div>
        <?php if($cryptId != null): ?>
            <div class="table-parent table-responsive" id="wallet-app" v-cloak>
                <table class="table table-striped" id="service-table">
                    <thead>
                    <tr>
                        <th><?php echo app('translator')->get('SL No.'); ?></th>
                        <th><?php echo app('translator')->get('Generate At'); ?></th>
                        <th><?php echo app('translator')->get('Currency'); ?></th>
                        <th><?php echo app('translator')->get('Address'); ?></th>
                        <th><?php echo app('translator')->get('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $cryptoWallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td data-label="<?php echo app('translator')->get('SL No.'); ?>"><?php echo e(++$key); ?></td>
                            <td data-label="<?php echo app('translator')->get('Generate At'); ?>"><?php echo e($item->created_at); ?></td>
                            <td data-label="<?php echo app('translator')->get('Currency'); ?>"><?php echo e(optional($item->crypto)->code); ?></td>
                            <td data-label="<?php echo app('translator')->get('Address'); ?>"><?php echo e($item->wallet_address); ?></td>
                            <td data-label="<?php echo app('translator')->get('Action'); ?>" class="action">
                                <a href="javascript:void(0)"
                                   class="btn-custom p-6"
                                   @click="copyAddress('<?php echo e($item->wallet_address); ?>')"><?php echo app('translator')->get('copy'); ?></a>
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
                    <?php echo e($cryptoWallets->appends($_GET)->links($theme.'partials.pagination')); ?>

                </ul>
            </nav>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('loadModal'); ?>
    <!-- Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel"></h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>

                <form action="<?php echo e(route('user.payout.moneyRequest')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row g-4">
                            <input type="hidden" class="currencyId" name="currencyId" value="">
                            <p class="mb-0 withdrawCharge text-danger"></p>
                            <label><?php echo app('translator')->get('Network'); ?></label>
                            <div class="input-box col-12 mt-1">
                                <input class="form-control" type="text" name="network"
                                       placeholder="<?php echo app('translator')->get('Network'); ?>"/>
                                <?php if($errors->has('network')): ?>
                                    <div
                                        class="error text-danger"><?php echo app('translator')->get($errors->first('network')); ?> </div>
                                <?php endif; ?>
                            </div>
                            <label><?php echo app('translator')->get('Wallet Address'); ?> <sup class="text-danger">*</sup></label>
                            <div class="input-box col-12 mt-1">
                                <input class="form-control" type="text" name="walletAddress"
                                       placeholder="<?php echo app('translator')->get('Wallet Address'); ?>"/>
                                <?php if($errors->has('walletAddress')): ?>
                                    <div
                                        class="error text-danger"><?php echo app('translator')->get($errors->first('walletAddress')); ?> </div>
                                <?php endif; ?>
                            </div>
                            <label><?php echo app('translator')->get('Withdraw Amount'); ?> <sup class="text-danger">*</sup></label>
                            <div class="input-box col-12 mt-1">
                                <input class="form-control" type="text" name="withdrawAmount"
                                       placeholder="<?php echo app('translator')->get('Withdraw Amount'); ?>"/>
                                <?php if($errors->has('withdrawAmount')): ?>
                                    <div
                                        class="error text-danger"><?php echo app('translator')->get($errors->first('withdrawAmount')); ?> </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                        <button type="submit" class="btn-custom"><?php echo app('translator')->get('Request Withdraw'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="depositModalLabel"></h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>

                <form action="<?php echo e(route('user.wallet.generate')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row g-4">
                            <input type="hidden" class="currencyId" name="currencyId" value="">
                            <p class="mb-0 depositCharge text-danger"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                        <button type="submit" class="btn-custom btn-generate"><?php echo app('translator')->get('Generate New Address'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
    <?php if($errors->any()): ?>
        <script>
            'use strict';
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            Notiflix.Notify.Failure(`<?php echo e(trans($error)); ?>`);
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
    <?php endif; ?>
    <script>
        "use script"
        $(document).on('click', '.btnWithdraw', function () {
            var obj = $(this).data('resource');

            if (obj.crypto.withdraw_type == 0) {
                $('.withdrawCharge').text(`Withdraw Charge ${obj.crypto.withdraw_charge}%`);
            } else {
                $('.withdrawCharge').text(`Withdraw Charge ${obj.crypto.withdraw_charge} ${obj.crypto.code}`);
            }
            $('.currencyId').val(obj.crypto_currency_id);
            $('#editModalLabel').text(`Make ${obj.crypto.code} Withdraw`);
        });

        $(document).on('click', '.btnDeposit', function () {
            var obj = $(this).data('resource');
            if (obj.crypto.deposit_type == 0) {
                $('.depositCharge').text(`Deposit Charge ${obj.crypto.deposit_charge}%`);
            } else {
                $('.depositCharge').text(`Deposit Charge ${obj.crypto.deposit_charge} ${obj.crypto.code}`);
            }
            $('.currencyId').val(obj.crypto_currency_id);
            $('.btn-generate').text(`Generate New ${obj.crypto.code} Address`);
            $('#depositModalLabel').text(`Make ${obj.crypto.code} Deposit`);
        });

        var newApp = new Vue({
            el: "#wallet-app",
            data: {},
            mounted() {
            },
            methods: {
                copyAddress(code) {
                    let text = code;
                    navigator.clipboard.writeText(text);
                    Notiflix.Notify.Success(`Copied: ${text}`);
                },
            },
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/wallet/index.blade.php ENDPATH**/ ?>