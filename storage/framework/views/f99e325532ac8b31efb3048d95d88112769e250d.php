<?php $__env->startSection('dynamic'); ?>

    <div class="col-lg-4">
        <div class="side-bar">
            <div class="identity-confirmation">
                <?php if(in_array($user->identity_verify,[0,3])  ): ?>
                    <?php if($user->identity_verify == 3): ?>
                        <div class="col-12">
                            <div class="bd-callout bd-callout-primary mx-2">
                                <i class="fa-3x fas fa-info-circle font-13"></i>
                                <span class="text-danger"><?php echo app('translator')->get('You previous request has been rejected'); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="<?php echo e(route('user.verificationSubmit')); ?>"
                          enctype="multipart/form-data" class="mt-0">
                        <?php echo csrf_field(); ?>
                        <div class="row g-4">
                            <div class="col-12">
                                <h4><?php echo app('translator')->get('Confirm Identity'); ?></h4>
                            </div>
                            <div class="row">
                                <div class="input-box col-12 mb-3">
                                    <label for=""><?php echo app('translator')->get('Identity Type'); ?></label>
                                    <select name="identity_type" id="identity_type" class="form-select"
                                            aria-label="Default select example">
                                        <option value="" selected disabled><?php echo app('translator')->get('Select Type'); ?></option>
                                        <?php $__currentLoopData = $identityFormList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sForm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($sForm->slug); ?>" <?php echo e(old('identity_type', @$identity_type) == $sForm->slug ? 'selected' : ''); ?>><?php echo app('translator')->get($sForm->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['identity_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="error text-danger"><?php echo app('translator')->get($message); ?> </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <?php if(isset($identityForm)): ?>
                                <?php $__currentLoopData = $identityForm->services_form; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($v->type == "text"): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <label
                                                    for="<?php echo e($k); ?>"><?php echo e(trans($v->field_level)); ?> <?php if($v->validation == 'required'): ?>
                                                        <span class="text-danger">*</span>
                                                    <?php endif; ?>
                                                </label>
                                                <div class="form-group input-box mb-3">
                                                    <input type="text" name="<?php echo e($k); ?>"
                                                           class="form-control "
                                                           value="<?php echo e(old($k)); ?>" id="<?php echo e($k); ?>"
                                                           <?php if($v->validation == 'required'): ?> required <?php endif; ?>>

                                                    <?php if($errors->has($k)): ?>
                                                        <div
                                                            class="error text-danger"><?php echo app('translator')->get($errors->first($k)); ?> </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif($v->type == "textarea"): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <label
                                                    for="<?php echo e($k); ?>"><?php echo e(trans($v->field_level)); ?> <?php if($v->validation == 'required'): ?>
                                                        <span
                                                            class="text-danger">*</span>
                                                    <?php endif; ?>
                                                </label>
                                                <div class="form-group input-box mb-3">
                                                        <textarea name="<?php echo e($k); ?>" id="<?php echo e($k); ?>"
                                                                  class="form-control "
                                                                  rows="5"
                                                                  placeholder="<?php echo e(trans('Type Here')); ?>"
                                                                          <?php if($v->validation == 'required'): ?><?php endif; ?>><?php echo e(old($k)); ?></textarea>
                                                    <?php $__errorArgs = [$k];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="error text-danger">
                                                        <?php echo e(trans($message)); ?>

                                                    </div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif($v->type == "file"): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <label><?php echo e(trans($v->field_level)); ?> <?php if($v->validation == 'required'): ?>
                                                        <span class="text-danger">*</span>
                                                    <?php endif; ?>
                                                </label>
                                                <div class="form-group input-box">
                                                    <br>
                                                    <div class="fileinput fileinput-new "
                                                         data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail "
                                                             data-trigger="fileinput">
                                                            <img class="wh-200-150"
                                                                 src="<?php echo e(getFile(config('location.default'))); ?>"
                                                                 alt="...">
                                                        </div>
                                                        <div
                                                            class="fileinput-preview fileinput-exists thumbnail wh-200-150 "></div>

                                                        <div class="img-input-div">
                                                                    <span class="btn btn-success btn-file">
                                                                        <span
                                                                            class="fileinput-new "> <?php echo app('translator')->get('Select'); ?> <?php echo e($v->field_level); ?></span>
                                                                        <span
                                                                            class="fileinput-exists"> <?php echo app('translator')->get('Change'); ?></span>
                                                                        <input type="file" name="<?php echo e($k); ?>"
                                                                               value="<?php echo e(old($k)); ?>" accept="image/*"
                                                                               <?php if($v->validation == "required"): ?><?php endif; ?>>
                                                                    </span>
                                                            <a href="#"
                                                               class="btn btn-remove fileinput-exists"
                                                               data-dismiss="fileinput"> <?php echo app('translator')->get('Remove'); ?></a>
                                                        </div>
                                                    </div>
                                                    <?php $__errorArgs = [$k];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="error text-danger">
                                                        <?php echo e(trans($message)); ?>

                                                    </div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12">
                                    <button type="submit"
                                            class="btn-custom"><?php echo app('translator')->get('Submit'); ?></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php elseif($user->identity_verify == 1): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="bd-callout bd-callout-primary mx-2">
                                <i class="fa-3x fas fa-info-circle font-13"></i>
                                <span class="text-warning"><?php echo app('translator')->get('Your KYC submission has been pending'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php elseif($user->identity_verify == 2): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="bd-callout bd-callout-primary mx-2">
                                <i class="fa-3x fas fa-info-circle font-13"></i>
                                <span class="text-success"><?php echo app('translator')->get('Your KYC already verified'); ?></span></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-lib'); ?>
    <link rel="stylesheet" href="<?php echo e(asset($themeTrue.'css/bootstrap-fileinput.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('extra-js'); ?>
    <script src="<?php echo e(asset($themeTrue.'js/bootstrap-fileinput.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        "use strict";
        $(document).on('change', "#identity_type", function () {
            let value = $(this).find('option:selected').val();
            window.location.href = "<?php echo e(route('user.identityVerify')); ?>/?identity_type=" + value
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'user.setting.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/profile/identity.blade.php ENDPATH**/ ?>