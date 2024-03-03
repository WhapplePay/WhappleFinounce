<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.dashboard')); ?>" aria-expanded="false">
                        <i data-feather="home" class="feather-icon text-info"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Dashboard'); ?></span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.identify-form')); ?>" aria-expanded="false">
                        <i data-feather="file-text" class="feather-icon text-danger"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('KYC / Identity Form'); ?></span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Api Settings'); ?></span></li>

                <li class="sidebar-item <?php echo e(menuActive(['admin.api-setting*'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.api-setting')); ?>" aria-expanded="false">
                        <i class="fas fa-cogs text-primary"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Api Settings'); ?></span>
                    </a>
                </li>


                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Currencies'); ?></span></li>

                <li class="sidebar-item <?php echo e(menuActive(['admin.*Crypto'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.listCrypto')); ?>" aria-expanded="false">
                        <i class="fab fa-bitcoin text-orange"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Crypto'); ?></span>
                    </a>

                </li>
                <li class="sidebar-item <?php echo e(menuActive(['admin.*Fiat'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.listFiat')); ?>" aria-expanded="false">
                        <i class="fas fa-ruble-sign text-bel"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Fiat'); ?></span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Payment Setting'); ?></span></li>
                <li class="sidebar-item <?php echo e(menuActive(['admin.payment.windows'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.payment.windows')); ?>" aria-expanded="false">
                        <i class="fas fa-stopwatch text-info"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Payment Windows'); ?></span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo e(menuActive(['admin.payment.methods','create.payment.methods','admin.edit.payment.methods'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.payment.methods')); ?>"
                       aria-expanded="false">
                        <i class="fas fa-credit-card text-bel"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Payment Methods'); ?></span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Withdraw Settings'); ?></span></li>

                <li class="sidebar-item <?php echo e(menuActive(['admin.payout.method'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.payout.method')); ?>" aria-expanded="false">
                        <i class="fas fa-address-card text-sunflower"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Withdraw Method'); ?></span>
                    </a>
                </li>

                <li class="sidebar-item <?php echo e(menuActive(['admin.payout-request'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.payout-request')); ?>" aria-expanded="false">
                        <i class="fas fa-hand-holding-usd text-danger"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Withdraw Request'); ?></span>
                    </a>
                </li>

                <li class="sidebar-item <?php echo e(menuActive(['admin.payout-log*'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.payout-log')); ?>" aria-expanded="false">
                        <i class="fas fa-history text-warning"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Withdraw Log'); ?></span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Manage Advertisements'); ?></span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fab fa-adversal text-pome"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Advertise Lists'); ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.advertise.list','enable')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Enabled'); ?></span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.advertise.list','disable')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Disable'); ?> </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Manage Trades'); ?></span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-chart-line text-tur"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Trades List'); ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.trade.list','all')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('All'); ?> </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.trade.list','running')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Running'); ?></span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.trade.list','reported')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Reported'); ?><sup
                                        class="badge badge-pill badge-danger mt-2 ml-1"><?php echo e($reported); ?></sup> </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.trade.list','complete')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Complete'); ?> </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('All Transaction '); ?></span></li>

                <li class="sidebar-item <?php echo e(menuActive(['admin.transaction*'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.transaction')); ?>" aria-expanded="false">
                        <i class="fas fa-exchange-alt text-danger"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Transaction'); ?></span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo e(menuActive(['admin.payment.log','admin.payment.search'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.payment.log')); ?>" aria-expanded="false">
                        <i class="fas fa-history text-success"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Deposit Log'); ?></span>
                    </a>
                </li>

                
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Manage User'); ?></span></li>

                <li class="sidebar-item <?php echo e(menuActive(['admin.users','admin.users.search','admin.user-edit*','admin.send-email*','admin.user*'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.users')); ?>" aria-expanded="false">
                        <i class="fas fa-users text-success"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('All User'); ?></span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.kyc.users.pending')); ?>"
                       aria-expanded="false">
                        <i class="fas fa-spinner text-primary"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Pending KYC'); ?></span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.kyc.users')); ?>"
                       aria-expanded="false">
                        <i class="fas fa-reply text-warning"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('KYC Log'); ?></span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.email-send')); ?>"
                       aria-expanded="false">
                        <i class="fas fa-envelope-open text-tur"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Send Email'); ?></span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Support Tickets'); ?></span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.ticket')); ?>" aria-expanded="false">
                        <i class="fas fa-ticket-alt text-info"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('All Tickets'); ?></span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.ticket',['open'])); ?>"
                       aria-expanded="false">
                        <i class="fas fa-spinner text-bel"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Open Ticket'); ?></span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.ticket',['closed'])); ?>"
                       aria-expanded="false">
                        <i class="fas fa-times-circle text-danger"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Closed Ticket'); ?></span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.ticket',['answered'])); ?>"
                       aria-expanded="false">
                        <i class="fas fa-reply text-success"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Answered Ticket'); ?></span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Controls'); ?></span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.basic-controls')); ?>" aria-expanded="false">
                        <i class="fas fa-cogs text-primary"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Basic Controls'); ?></span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-envelope text-success"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Email Settings'); ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.email-controls')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Email Controls'); ?></span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.email-template.show')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Email Template'); ?> </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-mobile-alt text-danger"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('SMS Settings'); ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.sms.config')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('SMS Controls'); ?></span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.sms-template')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('SMS Template'); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-bullhorn text-warning"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Push Notification'); ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.push.notify-config')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Configuration'); ?></span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.push.notify-template.show')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Template'); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-bell text-purple"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('In App Notification'); ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.notify-config')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Configuration'); ?></span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="<?php echo e(route('admin.notify-template.show')); ?>" class="sidebar-link">
                                <span class="hide-menu"><?php echo app('translator')->get('Template'); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.plugin.config')); ?>" aria-expanded="false">
                        <i class="fas fa-toolbox text-danger"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Plugin Configuration'); ?></span>
                    </a>
                </li>


                <li class="sidebar-item <?php echo e(menuActive(['admin.language.create','admin.language.edit*','admin.language.keywordEdit*'],3)); ?>">
                    <a class="sidebar-link" href="<?php echo e(route('admin.language.index')); ?>"
                       aria-expanded="false">
                        <i class="fas fa-language text-teal"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Manage Language'); ?></span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Subscriber'); ?></span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.subscriber.index')); ?>" aria-expanded="false">
                        <i class="fas fa-envelope-open text-indigo"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Subscriber List'); ?></span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu"><?php echo app('translator')->get('Theme Settings'); ?></span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.logo-seo')); ?>" aria-expanded="false">
                        <i class="fas fa-image text-purple"></i><span
                            class="hide-menu"><?php echo app('translator')->get('Manage Logo & SEO'); ?></span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo e(route('admin.breadcrumb')); ?>" aria-expanded="false">
                        <i class="fas fa-file-image text-orange"></i><span
                            class="hide-menu"><?php echo app('translator')->get('Manage Breadcrumb'); ?></span>
                    </a>
                </li>


                <li class="sidebar-item <?php echo e(menuActive(['admin.template.show*'],3)); ?>">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-clipboard-list text-indigo"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Manage Content'); ?></span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level base-level-line <?php echo e(menuActive(['admin.template.show*'],1)); ?>">

                        <?php $__currentLoopData = array_diff(array_keys(config('templates')),['message','template_media']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="sidebar-item <?php echo e(menuActive(['admin.template.show'.$name])); ?>">
                                <a class="sidebar-link <?php echo e(menuActive(['admin.template.show'.$name])); ?>"
                                   href="<?php echo e(route('admin.template.show',$name)); ?>">
                                    <span class="hide-menu"><?php echo app('translator')->get(ucfirst(kebab2Title($name))); ?></span>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </ul>
                </li>


                <?php
                    $segments = request()->segments();
                    $last  = end($segments);
                ?>
                <li class="sidebar-item <?php echo e(menuActive(['admin.content.create','admin.content.show*'],3)); ?>">
                    <a class="sidebar-link has-arrow <?php echo e(Request::routeIs('admin.content.show',$last) ? 'active' : ''); ?>"
                       href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-clipboard-list text-teal"></i>
                        <span class="hide-menu"><?php echo app('translator')->get('Content Settings'); ?></span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level base-level-line <?php echo e(menuActive(['admin.content.create','admin.content.show*'],1)); ?>">
                        <?php $__currentLoopData = array_diff(array_keys(config('contents')),['message','content_media']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="sidebar-item <?php echo e(($last == $name) ? 'active' : ''); ?> ">
                                <a class="sidebar-link <?php echo e(($last == $name) ? 'active' : ''); ?>"
                                   href="<?php echo e(route('admin.content.index',$name)); ?>">
                                    <span class="hide-menu"><?php echo app('translator')->get(ucfirst(kebab2Title($name))); ?></span>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>

                <li class="list-divider"></li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<?php /**PATH /opt/lampp/htdocs/whapplecoins/resources/views/admin/layouts/sidebar.blade.php ENDPATH**/ ?>