<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Add New'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="main row">
            <section class="edit-profile-section">
                <div class="row">
                    <div class="col-12">
                        <h4 class=""><?php echo app('translator')->get('New Advertisement'); ?></h4>
                        <form action="<?php echo e(route('user.advertisements.store')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row g-4">
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('I Want To'); ?> <i
                                            class="fas fa-info-circle ms-2 theme-color info-cursor"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="<?php echo app('translator')->get('What kind of advertisement do you wish to create? If you wish to sell bitcoins make sure you have bitcoins in your wallet.'); ?>"></i></label>

                                    <select class="form-select tradeType" aria-label="Default select example"
                                            name="type" required>
                                        <option selected disabled><?php echo app('translator')->get('Select One'); ?></option>
                                        <option
                                            value="sell" <?php echo e(old('type') == 'sell' ?'selected':''); ?> ><?php echo app('translator')->get('Sell'); ?></option>
                                        <option
                                            value="buy" <?php echo e(old('type') == 'buy' ?'selected':''); ?>><?php echo app('translator')->get('Buy'); ?></option>
                                    </select>
                                    <p class="mt-1 theme-color" id="info"></p>
                                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Cryptocurrency'); ?> <i
                                            class="fas fa-info-circle ms-2 theme-color info-cursor"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="<?php echo app('translator')->get('Which cryptocurrency do you wish to buy or sell?'); ?>"></i></label>
                                    <select class="form-select" aria-label="Default select example" name="crypto_id"
                                            id="cryptoChange" required>
                                        <option selected disabled><?php echo app('translator')->get('Select One'); ?></option>
                                        <?php $__empty_1 = true; $__currentLoopData = $cryptos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crypto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option
                                                value="<?php echo e($crypto->id); ?>"
                                                <?php echo e(old('crypto_id') == $crypto->id ?'selected':''); ?> data-code="<?php echo e($crypto->code); ?>"
                                                data-rate="<?php echo e($crypto->rate); ?>"><?php echo e($crypto->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php $__errorArgs = ['crypto_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-md-4">
                                    <label for=""><?php echo app('translator')->get('Payment Method'); ?> <i
                                            class="fas fa-info-circle ms-2 theme-color info-cursor"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="<?php echo app('translator')->get('Which payment method will be used to pay the fiat currency?'); ?>"></i></label><br>
                                    <select class="selectpicker" multiple name="gateway_id[]" required>
                                        <?php $__empty_1 = true; $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option
                                                value="<?php echo e($gateway->id); ?>" <?php echo e(old('gateway_id') == $gateway->id ?'selected':''); ?>><?php echo e($gateway->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php $__errorArgs = ['gateway_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-md-4">
                                    <label for=""><?php echo app('translator')->get('Currency'); ?> <i
                                            class="fas fa-info-circle ms-2 theme-color info-cursor"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="<?php echo app('translator')->get('Which fiat currency needs exchange from cryptocurrency?'); ?>"></i></label>
                                    <select class="form-select " aria-label="Default select example" id="fiatChange"
                                            name="fiat_id" required>
                                        <option selected disabled><?php echo app('translator')->get('Select One'); ?></option>
                                        <?php $__currentLoopData = $fiats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fiat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($fiat->id); ?>"
                                                <?php echo e(old('fiat_id') == $fiat->id ?'selected':''); ?> data-code="<?php echo e($fiat->code); ?>"
                                                data-rate="<?php echo e($fiat->rate); ?>"><?php echo e($fiat->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['fiat_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-md-4">
                                    <label for=""><?php echo app('translator')->get('Price Type'); ?></label>
                                    <select class="form-select priceType" aria-label="Default select example"
                                            name="price_type" required>
                                        <option selected
                                                value="margin" <?php echo e(old('price_type') == 'margin' ?'selected':''); ?>><?php echo app('translator')->get('Margin'); ?></option>
                                        <option
                                            value="fixed" <?php echo e(old('price_type') == 'fixed' ?'selected':''); ?>><?php echo app('translator')->get('Fixed'); ?></option>
                                    </select>
                                    <?php $__errorArgs = ['price_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="" class="marginLabel"><?php echo app('translator')->get('Margin'); ?> <i
                                            class="fas fa-info-circle ms-2 theme-color info-cursor"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="<?php echo app('translator')->get('The margin you want over the bitcoin market price. Use a negative value for buying or selling under the market price to attract more contacts.'); ?>"></i></label>
                                    <div class="input-group">
                                        <input class="form-control inputPrice" type="number" step="0.001" min="-5"
                                               name="price"
                                               value="<?php echo e(old('price')); ?>" required/>
                                        <div class="input-group-prepend">
                                            <span class="form-control theme-color marginCode">%</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Payment Window'); ?> <i
                                            class="fas fa-info-circle ms-2 theme-color info-cursor"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="<?php echo app('translator')->get('For Buyer: Within how many minutes do you promise to initiate the payment. For Seller: Within how many minutes you want to get paid'); ?>"></i></label>
                                    <select class="form-select" aria-label="Default select example"
                                            name="payment_window_id" required>
                                        <option selected disabled><?php echo app('translator')->get('Select One'); ?></option>
                                        <?php $__empty_1 = true; $__currentLoopData = $paymentWindows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentWindow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option
                                                value="<?php echo e($paymentWindow->id); ?>" <?php echo e(old('payment_window_id') == $paymentWindow->id ?'selected':''); ?>><?php echo e($paymentWindow->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php $__errorArgs = ['payment_window_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Minimum Limit'); ?> <i
                                            class="fas fa-info-circle ms-2 theme-color info-cursor"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="<?php echo app('translator')->get('Minimum transaction limit in one trade.?'); ?>"></i></label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" step="0.001" min="0"
                                               name="minimum_limit"
                                               value="<?php echo e(old('minimum_limit')); ?>"
                                               placeholder="<?php echo app('translator')->get(''); ?>" required/>
                                        <div class="input-group-prepend">
                                            <span class="form-control theme-color currencyCode"></span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['minimum_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Maximum Limit'); ?> <i
                                            class="fas fa-info-circle ms-2 theme-color info-cursor"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="<?php echo app('translator')->get('Maximum transaction limit in one trade.?'); ?>"></i></label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" step="0.001" min="0"
                                               name="maximum_limit"
                                               value="<?php echo e(old('maximum_limit')); ?>"
                                               placeholder="<?php echo app('translator')->get(''); ?>" required/>
                                        <div class="input-group-prepend">
                                            <span class="form-control theme-color currencyCode"></span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['maximum_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-md-12">
                                    <label for=""><?php echo app('translator')->get('Price Equation:'); ?></label>
                                    <label for="" class="theme-color priceEquation"></label>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Payment Details'); ?></label>
                                    <textarea class="form-control" id="textAreaExample" name="payment_details"
                                              rows="4"><?php echo e(old('payment_details')); ?></textarea>
                                    <?php $__errorArgs = ['payment_details'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-md-6">
                                    <label for=""><?php echo app('translator')->get('Terms of Trade'); ?></label>
                                    <textarea class="form-control" id="textAreaExample" rows="4"
                                              name="terms_of_trade"><?php echo e(old('terms_of_trade')); ?></textarea>
                                    <?php $__errorArgs = ['terms_of_trade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-danger  mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-box col-12">
                                    <button type="submit" class="btn-custom"><?php echo app('translator')->get('Submit'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('style'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        'use script'
        $('.selectpicker').select2({
            width: '100%'
        });

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));


        var tradeCharge = <?php echo e(config('basic.tradeCharge')); ?>

        $(document).on('change', '.priceType', function (e) {
            $('.marginLabel').html('');

            if ($(this).val() == 'fixed') {
                $('.marginLabel').append(`Fixed <i class="fas fa-info-circle ms-2 theme-color info-cursor"
                                                                       title="<?php echo app('translator')->get('If you want to sell or buy your coins in fixed price then use this.'); ?>"></i>`);
                $('.marginCode').text($('#fiatChange').find('option:selected').data('code'))
                $('.priceEquation').text('');
                $('.inputPrice').val(0);
            } else {
                $('.marginLabel').append(`Margin <i class="fas fa-info-circle ms-2 theme-color info-cursor"
                                                                       title="<?php echo app('translator')->get('The margin you want over the bitcoin market price. Use a negative value for buying or selling under the market price to attract more contacts.'); ?>"></i>`);
                $('.marginCode').text('%');
            }
        });
        $(document).on('change', '.tradeType', function (e) {
            if ($(this).val() == 'sell') {
                $('#info').text(`For selling ${tradeCharge}% will be charged for each completed trade.`);
            } else {
                $('#info').text('');
            }
        });
        $(document).on('change', '#cryptoChange', function (e) {
            calPrice();
        });
        $(document).on('change', '#fiatChange', function (e) {
            $('.currencyCode').text($(this).find(':selected').data('code'))
            calPrice();
        });

        $(document).on('keyup', '.inputPrice', function (e) {
            calPrice($(this).val());
        });

        function calPrice(inputPrice = null) {
            var fiat = $('#fiatChange').find('option:selected').data('code');
            var crypto = $('#cryptoChange').find('option:selected').data('code');
            var fiatRate = $('#fiatChange').find('option:selected').data('rate');
            var cryptoRate = $('#cryptoChange').find('option:selected').data('rate');
            var priceType = $('.priceType').find('option:selected').val();

            var totalPrice = (parseFloat(fiatRate) * parseFloat(cryptoRate)).toFixed(2);
            var tradePrice = totalPrice;
            if (inputPrice) {
                if (priceType == 'margin') {
                    var tradePrice = ((parseFloat(totalPrice) * parseFloat(inputPrice) / 100) + parseFloat(totalPrice)).toFixed(2)
                } else {
                    var tradePrice = inputPrice;
                }
            }


            if (tradePrice != 'NaN') {
                var priceEquation = `${tradePrice} ${fiat}/${crypto}`
                $('.priceEquation').text(priceEquation);
            }
        }

    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/user/advertise/create.blade.php ENDPATH**/ ?>