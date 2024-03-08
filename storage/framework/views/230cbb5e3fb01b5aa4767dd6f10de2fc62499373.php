<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Push Notification'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-body">
                    <form method="post" action="" class="needs-validation base-form">
                        <?php echo csrf_field(); ?>
                        <div class="row my-3">
                            <div class="form-group col-sm-6 col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('Server Key'); ?></label>
                                <input type="text" name="server_key"
                                       value="<?php echo e(old('server_key',$control->server_key)); ?>"
                                       required="required" class="form-control ">
                                <?php $__errorArgs = ['server_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e(trans($message)); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-sm-6 col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('Vapid Key'); ?></label>
                                <input type="text" name="vapid_key"
                                       value="<?php echo e(old('vapid_key',$control->vapid_key)); ?>" required="required"
                                       class="form-control ">
                                <?php $__errorArgs = ['vapid_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e(trans($message)); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-sm-6 col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('Api Key'); ?></label>
                                <input type="text" name="api_key"
                                       value="<?php echo e(old('api_key',$control->api_key)); ?>"
                                       required="required"
                                       class="form-control ">
                                <?php $__errorArgs = ['api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e(trans($message)); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-sm-6 col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('Auth Domain'); ?></label>
                                <input type="text" name="auth_domain"
                                       value="<?php echo e(old('auth_domain',$control->auth_domain)); ?>"
                                       required="required"
                                       class="form-control ">
                                <?php $__errorArgs = ['auth_domain'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e(trans($message)); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-sm-4 col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('Project Id'); ?></label>
                                <input type="text" name="project_id"
                                       value="<?php echo e(old('project_id',$control->project_id)); ?>"
                                       required="required"
                                       class="form-control ">
                                <?php $__errorArgs = ['project_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e(trans($message)); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-sm-4 col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('Storage Bucket'); ?></label>
                                <input type="text" name="storage_bucket"
                                       value="<?php echo e(old('storage_bucket',$control->storage_bucket)); ?>"
                                       required="required"
                                       class="form-control ">
                                <?php $__errorArgs = ['storage_bucket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e(trans($message)); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-sm-4 col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('Messaging Sender Id'); ?></label>
                                <input type="text" name="messaging_sender_id"
                                       value="<?php echo e(old('messaging_sender_id',$control->messaging_sender_id)); ?>"
                                       required="required"
                                       class="form-control ">
                                <?php $__errorArgs = ['messaging_sender_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e(trans($message)); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-sm-4 col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('App Id'); ?></label>
                                <input type="text" name="app_id"
                                       value="<?php echo e(old('app_id',$control->app_id)); ?>"
                                       required="required"
                                       class="form-control ">
                                <?php $__errorArgs = ['app_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e(trans($message)); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-sm-4 col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('Measurement Id'); ?></label>
                                <input type="text" name="measurement_id"
                                       value="<?php echo e(old('measurement_id',$control->measurement_id)); ?>"
                                       required="required"
                                       class="form-control ">
                                <?php $__errorArgs = ['measurement_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e(trans($message)); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-sm-6  col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('User Foreground'); ?></label>
                                <div class="custom-switch-btn">
                                    <input type='hidden' value='1' name='user_foreground'>
                                    <input type="checkbox" name="user_foreground" class="custom-switch-checkbox"
                                           id="user_foreground"
                                           value="0" <?php echo e(($control->user_foreground == 0) ? 'checked' : ''); ?> >
                                    <label class="custom-switch-checkbox-label" for="user_foreground">
                                        <span class="custom-switch-checkbox-inner"></span>
                                        <span class="custom-switch-checkbox-switch"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-sm-6  col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('User Background'); ?></label>
                                <div class="custom-switch-btn">
                                    <input type='hidden' value='1' name='user_background'>
                                    <input type="checkbox" name="user_background" class="custom-switch-checkbox"
                                           id="user_background"
                                           value="0" <?php echo e(($control->user_background == 0) ? 'checked' : ''); ?> >
                                    <label class="custom-switch-checkbox-label" for="user_background">
                                        <span class="custom-switch-checkbox-inner"></span>
                                        <span class="custom-switch-checkbox-switch"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-sm-6  col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('Admin Foreground'); ?></label>
                                <div class="custom-switch-btn">
                                    <input type='hidden' value='1' name='admin_foreground'>
                                    <input type="checkbox" name="admin_foreground" class="custom-switch-checkbox"
                                           id="admin_foreground"
                                           value="0" <?php echo e(($control->admin_foreground == 0) ? 'checked' : ''); ?> >
                                    <label class="custom-switch-checkbox-label" for="admin_foreground">
                                        <span class="custom-switch-checkbox-inner"></span>
                                        <span class="custom-switch-checkbox-switch"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-sm-6  col-12">
                                <label class="font-weight-bold"><?php echo app('translator')->get('Admin Background'); ?></label>
                                <div class="custom-switch-btn">
                                    <input type='hidden' value='1' name='admin_background'>
                                    <input type="checkbox" name="admin_background" class="custom-switch-checkbox"
                                           id="admin_background"
                                           value="0" <?php echo e(($control->admin_background == 0) ? 'checked' : ''); ?> >
                                    <label class="custom-switch-checkbox-label" for="admin_background">
                                        <span class="custom-switch-checkbox-inner"></span>
                                        <span class="custom-switch-checkbox-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                                class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                                    class="fas fa-save pr-2"></i> <?php echo app('translator')->get('Save Changes'); ?></span></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title  font-weight-bold"><?php echo app('translator')->get('Instructions'); ?></h4>
                        </div>

                        <div class="col-md-6">
                            <a href="https://www.youtube.com/watch?v=F_l69SMj6XU" target="_blank"
                               class="btn btn-primary btn-sm mb-2 text-white float-right" type="button">
                                <span class="btn-label"><i class="fab fa-youtube"></i></span>
                                <?php echo app('translator')->get('How to set up it?'); ?>
                            </a>
                        </div>
                    </div>

                    <?php echo app('translator')->get('Push notification provides realtime communication between servers, apps and devices.
                    When something happens in your system, it can update web-pages, apps and devices.
                    When an event happens on an app, the app can notify all other apps and your system
                    <br><br>
                    Get your free API keys'); ?>
                    <a href="https://console.firebase.google.com/" target="_blank"><?php echo app('translator')->get('Create an account'); ?>
                        <i class="fas fa-external-link-alt"></i></a>
                    <?php echo app('translator')->get(', then create a Firebase Project, then create a web app in created Project
                    Go to web app configuration details to get Vapid key, Api key, Auth domain, Project id, Storage bucket, Messaging sender id, App id, Measurement id.'); ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/pushNotify/controls.blade.php ENDPATH**/ ?>