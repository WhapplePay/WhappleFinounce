<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($user->username); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="m-0 m-md-4 my-4 m-md-0">
        <div class="row">

            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo app('translator')->get('Profile'); ?></h4>
                        <div class="form-group">
                            <div class="image-input">
                                <img id="image_preview_container" class="preview-image"
                                     src="<?php echo e(getFile(config('location.user.path').$user->image)); ?>"
                                     alt="preview image">
                            </div>
                        </div>
                        <h3> <?php echo app('translator')->get(ucfirst($user->username)); ?></h3>
                        <p><?php echo app('translator')->get('Joined At'); ?> <?php echo app('translator')->get($user->created_at->format('d M,Y h:i A')); ?> </p>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo app('translator')->get('User information'); ?></h4>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo app('translator')->get('Email'); ?>
                                <span><?php echo e($user->email); ?></span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo app('translator')->get('Username'); ?>
                                <span><?php echo e($user->username); ?></span></li>

                            <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo app('translator')->get('Status'); ?>
                                <span
                                    class="badge badge-<?php echo e(($user->status==1) ? 'success' :'danger'); ?> success badge-pill"><?php echo e(($user->status==1) ? trans('Active') : trans('Inactive')); ?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo app('translator')->get('Complete Trades'); ?>
                                <span class="badge badge-success"><?php echo e($user->completed_trade); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo app('translator')->get('Avg Speed'); ?>
                                <?php if($user->completed_trade>0): ?>
                                    <span><?php echo e($user->total_min/$user->completed_trade); ?> <?php echo app('translator')->get('Minutes'); ?> </span>
                                <?php else: ?>
                                    <span>0 <?php echo app('translator')->get('Minutes'); ?> </span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo app('translator')->get('User action'); ?></h4>


                        <div class="btn-list ">
                                <button class="btn btn-primary btn-sm" type="button" data-toggle="modal"
                                        data-target="#balance">
                                    <span class="btn-label"><i class="fas fa-hand-holding-usd"></i></span>
                                    <?php echo app('translator')->get('Add/Subtract Fund'); ?>
                                </button>

                                <a href="<?php echo e(route('admin.user.transaction',$user->id)); ?>"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-exchange-alt"></i></span> <?php echo app('translator')->get('Transaction Log'); ?>
                                </a>


                                <a href="<?php echo e(route('admin.payment.log',$user->id)); ?>"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-money-bill-alt"></i></span> <?php echo app('translator')->get('Deposit Log'); ?>
                                </a>


                                <a href="<?php echo e(route('admin.payout-log',$user->id)); ?>"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-hand-holding"></i></span> <?php echo app('translator')->get('Withdraw Log'); ?>
                                </a>

                                <a href="<?php echo e(route('admin.send-email',$user->id)); ?>"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-envelope-open"></i></span> <?php echo app('translator')->get('Send Email'); ?>
                                </a>


                                <a href="<?php echo e(route('admin.user.userKycHistory',$user)); ?>"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-file-invoice"></i></span> <?php echo app('translator')->get('KYC Records'); ?>
                                </a>


                        </div>


                    </div>
                </div>

            </div>

            <div class="col-md-8">

                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo e(ucfirst($user->username)); ?> <?php echo app('translator')->get('Information'); ?></h4>
                        <form method="post" action="<?php echo e(route('admin.user-update', $user->id)); ?>"
                              enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('First Name'); ?></label>
                                        <input class="form-control" type="text" name="firstname"
                                               value="<?php echo e($user->firstname); ?>"
                                               required>
                                        <?php $__errorArgs = ['firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('Last Name'); ?></label>
                                        <input class="form-control" type="text" name="lastname"
                                               value="<?php echo e($user->lastname); ?>"
                                               required>
                                        <?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('Username'); ?></label>
                                        <input class="form-control" type="text" name="username"
                                               value="<?php echo e($user->username); ?>" required>
                                        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('Email'); ?></label>
                                        <input class="form-control" type="email" name="email" value="<?php echo e($user->email); ?>"
                                               required>
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('Phone Number'); ?></label>
                                        <input class="form-control" type="text" name="phone" value="<?php echo e($user->phone); ?>">
                                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('Preferred language'); ?></label>

                                        <select name="language_id" id="language_id" class="form-control">
                                            <option value="" disabled><?php echo app('translator')->get('Select Language'); ?></option>
                                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $la): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($la->id); ?>"
                                                    <?php echo e(old('language_id', $user->language_id) == $la->id ? 'selected' : ''); ?>><?php echo app('translator')->get($la->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>

                                        <?php $__errorArgs = ['language_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('Address'); ?></label>
                                        <textarea class="form-control" name="address"
                                                  rows="2"><?php echo e($user->address); ?></textarea>
                                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('Incomplete Trade Limit For Ad'); ?></label>
                                        <input type="number" class="form-control"
                                               name="trade_limit"
                                               value="<?php echo e($user->trade_limit ?? config('basic.incompleteLimit')); ?>"/>
                                        <?php $__errorArgs = ['trade_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><?php echo app('translator')->get('Status'); ?></label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='status'>
                                                <input type="checkbox" name="status" class="custom-switch-checkbox"
                                                       id="status" <?php echo e($user->status == 0 ? 'checked' : ''); ?> >
                                                <label class="custom-switch-checkbox-label" for="status">
                                                    <span class="status custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <label><?php echo app('translator')->get('Email Verification'); ?></label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='email_verification'>
                                                <input type="checkbox" name="email_verification"
                                                       class="custom-switch-checkbox"
                                                       id="email_verification" <?php echo e($user->email_verification == 0 ? 'checked' : ''); ?>>
                                                <label class="custom-switch-checkbox-label" for="email_verification">
                                                    <span class="verify custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label><?php echo app('translator')->get('SMS Verification'); ?></label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='sms_verification'>
                                                <input type="checkbox" name="sms_verification"
                                                       class="custom-switch-checkbox"
                                                       id="sms_verification" <?php echo e($user->sms_verification == 0 ? 'checked' : ''); ?>>
                                                <label class="custom-switch-checkbox-label" for="sms_verification">
                                                    <span class="verify custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label><?php echo app('translator')->get('2FA Secturity'); ?></label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='0' name='two_fa_verify'>
                                                <input type="checkbox" name="two_fa_verify"
                                                       class="custom-switch-checkbox"
                                                       id="two_fa_verify" <?php echo e($user->two_fa_verify == 1 ? 'checked' : ''); ?>>
                                                <label class="custom-switch-checkbox-label" for="two_fa_verify">
                                                    <span class="custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn-wrapper mt-md-3  text-center text-md-left">
                                <button type="submit"
                                        class=" btn waves-effect waves-light btn-rounded btn-primary btn-block">
                                    <span><?php echo app('translator')->get('Update User'); ?></span></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                            <div class="card shadow border-right">
                                <div class="card-body">
                                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                                        <div>
                                            <div class="d-inline-flex align-items-center">
                                                <h2 class="text-dark mb-1 font-weight-medium"><?php echo e($wallet->balance); ?></h2>
                                            </div>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate"><?php echo e(optional($wallet->crypto)->code); ?></h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0">
                                            <span class="opacity-7 text-muted"><img width="30" height="30"
                                                                                    src="<?php echo e(getFile(config('location.currency.path').optional($wallet->crypto)->image)); ?>"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php endif; ?>
                </div>
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo app('translator')->get('Password Change'); ?></h4>

                        <form method="post" action="<?php echo e(route('admin.userPasswordUpdate',$user->id)); ?>"
                              enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('New Password'); ?></label>
                                        <input id="new_password" type="password" class="form-control" name="password"
                                               autocomplete="current-password">
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group ">
                                        <label><?php echo app('translator')->get('Confirm Password'); ?></label>
                                        <input id="confirm_password" type="password" name="password_confirmation"
                                               autocomplete="current-password" class="form-control">
                                        <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn-wrapper mt-md-3 text-center text-md-left">
                                <button type="submit"
                                        class="btn waves-effect waves-light btn-rounded btn-primary btn-block">
                                    <span><?php echo app('translator')->get('Update Password'); ?></span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal fade" id="balance">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" action="<?php echo e(route('admin.user-balance-update',$user->id)); ?>"
                      enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <!-- Modal Header -->
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title"><?php echo app('translator')->get('Add / Subtract Balance'); ?></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="form-group ">
                            <label><?php echo app('translator')->get('Select Wallet'); ?></label>
                            <select name="wallet" class="form-control wallet">
                                <option value="" disabled selected><?php echo app('translator')->get('Select One'); ?></option>
                                <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($wallet->id); ?>" data-code="<?php echo e(optional($wallet->crypto)->code); ?>"
                                            <?php if(@request()->wallet == $wallet->id): ?> selected <?php endif; ?>>
                                        <?php echo e(optional($wallet->crypto)->code); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?php echo app('translator')->get('Amount'); ?></label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="balance" id="balance">
                                <div class="input-group-append">
                                    <span class="form-control" id="code"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-switch-btn w-md-100">
                                <input type='hidden' value='1' name='add_status'>
                                <input type="checkbox" name="add_status" class=" custom-switch-checkbox" id="add_status"
                                       value="0">
                                <label class="custom-switch-checkbox-label" for="add_status">
                                    <span class="modal_status custom-switch-checkbox-inner"></span>
                                    <span class="custom-switch-checkbox-switch"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span><?php echo app('translator')->get('Close'); ?></span>
                        </button>
                        <button type="submit" class=" btn btn-primary balanceSave"><span><?php echo app('translator')->get('Submit'); ?></span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .modal_status.custom-switch-checkbox-inner:before {
            content: "+ Add Balance";
        }

        .modal_status.custom-switch-checkbox-inner:after {
            content: "- Substruct Balance";
        }

        .status.custom-switch-checkbox-inner:before {
            content: "Active";
        }

        .status.custom-switch-checkbox-inner:after {
            content: "Banned";
        }

        .verify.custom-switch-checkbox-inner:before {
            content: "Verfied";
        }

        .verify.custom-switch-checkbox-inner:after {
            content: "Unverfied";
        }

    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>
    <script>
        "use strict";
        $(document).ready(function () {
            $(document).on('click', '#image-label', function () {
                $('#image').trigger('click');
            });
            $(document).on('change', '#image', function () {
                var _this = $(this);
                var newimage = new FileReader();
                newimage.readAsDataURL(this.files[0]);
                newimage.onload = function (e) {
                    $('#image_preview_container').attr('src', e.target.result);
                }
            });
            $(document).on('click', '.balanceSave', function () {
                var bala = $('#balance').text();
            });

            $(document).on('change', '.wallet', function () {
                var code = $(this).select2().find(":selected").data("code");
                $('#code').text(code);
            });

            $('select').select2({
                selectOnClose: true,
                width: "100%"
            });
        });
    </script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/users/edit-user.blade.php ENDPATH**/ ?>