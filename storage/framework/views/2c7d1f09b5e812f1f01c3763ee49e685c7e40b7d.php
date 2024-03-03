<!-- sidebar -->
<div id="sidebar" class="">
    <div class="sidebar-top">
        <a class="navbar-brand" href="<?php echo e(route('home')); ?>"> <img
                src="<?php echo e(getFile(config('location.logoIcon.path') . 'logo.png')); ?>" alt=""/></a>
        <button class="sidebar-toggler d-md-none" onclick="toggleSideMenu()">
            <i class="fal fa-times"></i>
        </button>
    </div>
    <ul class="main">
        <li>
            <a class="<?php echo e(menuActive('user.home')); ?>" href="<?php echo e(route('user.home')); ?>"><i
                    class="fal fa-th-large"></i><?php echo app('translator')->get('Dashboard'); ?></a>
        </li>
        <li>
            <a class="<?php echo e(menuActive('user.wallet.list')); ?>" href="<?php echo e(route('user.wallet.list')); ?>"><i
                    class="fal fa-wallet"></i> <?php echo app('translator')->get('My wallet'); ?></a>
        </li>
        <li>
            <a class="<?php echo e(menuActive('user.advertisements.*')); ?>" href="<?php echo e(route('user.advertisements.list')); ?>"><i
                    class="fal fa-ad"></i><?php echo app('translator')->get('Advertisements'); ?></a>
        </li>
        <li>
            <a class="<?php echo e(menuActive('user.buyCurrencies.*')); ?>"
               href="<?php echo e(route('user.buyCurrencies.list','all')); ?>"><i
                    class="fal fa-money-check-alt"></i><?php echo app('translator')->get('Buy Currency'); ?></a>
        </li>
        <li>
            <a class="<?php echo e(menuActive('user.sellCurrencies.*')); ?>"
               href="<?php echo e(route('user.sellCurrencies.list','all')); ?>"><i
                    class="fas fa-hand-holding-magic"></i><?php echo app('translator')->get('Sell Currency'); ?></a>
        </li>
        <li>
            <a
                class="dropdown-toggle <?php echo e(menuActive('user.trade.list')); ?>"
                data-bs-toggle="collapse"
                href="#dropdownCollapsible3"
                role="button"
                aria-expanded="false"
                aria-controls="collapseExample">
                <i class="fal fa-file-spreadsheet"></i></i></i><?php echo app('translator')->get('Trade List'); ?>
            </a>
            <div class="collapse" id="dropdownCollapsible3">
                <ul class="">
                    <li>
                        <a href="<?php echo e(route('user.trade.list','running')); ?>"><i class="fal fa-th-large"></i><?php echo app('translator')->get('Running'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('user.trade.list','complete')); ?>"><i
                                class="fal fa-th-large"></i><?php echo app('translator')->get('Complete'); ?></a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <a class="<?php echo e(menuActive('user.transaction')); ?>" href="<?php echo e(route('user.transaction')); ?>"><i
                    class="fal fa-exchange-alt"></i><?php echo app('translator')->get('Transactions'); ?></a>
        </li>
        <li>
            <a class="<?php echo e(menuActive('user.deposit.history')); ?>" href="<?php echo e(route('user.deposit.history')); ?>"><i
                    class="fal fa-wallet"></i><?php echo app('translator')->get('Deposit History'); ?></a>
        </li>
        <li>
            <a class="<?php echo e(menuActive('user.payout.history')); ?>" href="<?php echo e(route('user.payout.history')); ?>"><i
                    class="fas fa-hand-holding-usd"></i><?php echo app('translator')->get('Payout History'); ?></a>
        </li>
        <li>
            <a class="<?php echo e(menuActive('user.ticket.list')); ?>" href="<?php echo e(route('user.ticket.list')); ?>"><i
                    class="fas fa-headset"></i>
                </i><?php echo app('translator')->get('Support Tickets'); ?></a>
        </li>
        <li>
            <a class="<?php echo e(menuActive('user.profile')); ?>" href="<?php echo e(route('user.profile')); ?>"><i
                    class="fal fa-user-edit"></i><?php echo app('translator')->get('Edit Profile'); ?></a>
        </li>
    </ul>
    <div class="company-setting-dropdown-items">
        <button type="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo e(getFile(config('location.logoIcon.path').'logo.png')); ?>" alt="<?php echo e(config('basic.site_title')); ?>"/>
            <span><?php echo e(config('basic.site_title')); ?></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-start">
            <li>
                <a href="<?php echo e(route('user.list.setting')); ?>" class="dropdown-item">
                    <i class="fal fa-cog"></i>
                    <span><?php echo app('translator')->get('Settings'); ?></span>
                </a>
            </li>
        </ul>
    </div>
</div>

<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/themes/peerToPeer/partials/sidebar.blade.php ENDPATH**/ ?>