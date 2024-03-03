<?php $__env->startSection('title',trans('Profile Settings')); ?>
<?php $__env->startSection('content'); ?>
    <!-- main -->
    <div class="container-fluid">
        <div class="main row">
            <!-- edit profile section -->
            <section class="edit-profile-section">
                <div class="row">
                    <div class="col-12">
                        <form action="<?php echo e(route('user.updateInformation')); ?>" class="mt-0" method="post" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="sidebar-wrapper">
                                <div class="cover">
                                    <div class="img">
                                        <img id="cover" src="<?php echo e(getFile(config('location.user.path').auth()->user()->cover_image)); ?>" alt="..." class="img-fluid"/>
                                        <button class="upload-img">
                                            <i class="fal fa-camera" aria-hidden="true"></i>
                                            <input
                                                class="form-control"
                                                name="coverImage"
                                                accept="image/*"
                                                type="file"
                                                onchange="previewImage('cover')"
                                            />
                                        </button>
                                    </div>
                                </div>
                                <div class="profile">
                                    <div class="img">
                                        <img id="profile"
                                             src="<?php echo e(getFile(config('location.user.path').auth()->user()->image)); ?>"
                                             alt="..."
                                             class="img-fluid"/>
                                        <button class="upload-img">
                                            <i class="fal fa-camera" aria-hidden="true"></i>
                                            <input
                                                class="form-control"
                                                name="image"
                                                accept="image/*"
                                                type="file"
                                                onchange="previewImage('profile')"
                                            />
                                        </button>
                                    </div>
                                    <div>
                                        <h5 class="name"><?php echo app('translator')->get(auth()->user()->fullname); ?></h5>
                                        <span><?php echo e(auth()->user()->email); ?></span>
                                    </div>
                                </div>
                            </div>

                            <h4 class="mb-3 mt-5"><?php echo app('translator')->get('Basic Info'); ?></h4>
                            <div class="row g-4">
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('First Name'); ?></label>
                                    <input class="form-control" type="text" name="firstname"
                                           value="<?php echo e(old('firstname')?: $user->firstname); ?>"
                                           placeholder="<?php echo app('translator')->get('First Name'); ?>"/>
                                    <?php if($errors->has('firstname')): ?>
                                        <div
                                            class="error text-danger"><?php echo app('translator')->get($errors->first('firstname')); ?> </div>
                                    <?php endif; ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Last Name'); ?></label>
                                    <input class="form-control" type="text" name="lastname"
                                           value="<?php echo e(old('lastname')?: $user->lastname); ?>"
                                           placeholder="<?php echo app('translator')->get('Last Name'); ?>"/>
                                    <?php if($errors->has('lastname')): ?>
                                        <div
                                            class="error text-danger"><?php echo app('translator')->get($errors->first('lastname')); ?> </div>
                                    <?php endif; ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Username'); ?></label>
                                    <input class="form-control" type="text" name="username"
                                           value="<?php echo e(old('username')?: $user->username); ?>"
                                           placeholder="<?php echo app('translator')->get('Username'); ?>"/>
                                    <?php if($errors->has('username')): ?>
                                        <div
                                            class="error text-danger"><?php echo app('translator')->get($errors->first('username')); ?> </div>
                                    <?php endif; ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Email Address'); ?></label>
                                    <input class="form-control" type="email" value="<?php echo e($user->email); ?>" readonly/>
                                    <?php if($errors->has('email')): ?>
                                        <div
                                            class="error text-danger"><?php echo app('translator')->get($errors->first('email')); ?> </div>
                                    <?php endif; ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Phone number'); ?></label>
                                    <input class="form-control" type="text" value="<?php echo e($user->phone); ?>" readonly/>
                                    <?php if($errors->has('phone')): ?>
                                        <div
                                            class="error text-danger"><?php echo app('translator')->get($errors->first('phone')); ?> </div>
                                    <?php endif; ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Preferred language'); ?></label>
                                    <select name="language_id" id="language_id" class="form-select"
                                            aria-label="Default select example">
                                        <option value="" disabled><?php echo app('translator')->get('Select Language'); ?></option>
                                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $la): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($la->id); ?>"

                                                <?php echo e(old('language_id', $user->language_id) == $la->id ? 'selected' : ''); ?>><?php echo app('translator')->get($la->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if($errors->has('language_id')): ?>
                                        <div
                                            class="error text-danger"><?php echo app('translator')->get($errors->first('language_id')); ?> </div>
                                    <?php endif; ?>
                                </div>
                                <div class="input-box col-12">
                                    <label for=""><?php echo app('translator')->get('Address'); ?></label>
                                    <textarea class="form-control" name="address" cols="30" rows="3"
                                              placeholder="<?php echo app('translator')->get('Address'); ?>"><?php echo app('translator')->get($user->address); ?></textarea>
                                    <?php if($errors->has('address')): ?>
                                        <div
                                            class="error text-danger"><?php echo app('translator')->get($errors->first('address')); ?> </div>
                                    <?php endif; ?>
                                </div>
                                <div class="input-box col-12">
                                    <button type="submit" class="btn-custom"><?php echo app('translator')->get('Submit changes'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/profile/myprofile.blade.php ENDPATH**/ ?>