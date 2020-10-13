<?php $__env->startSection('title',__('pages/users.view') . " | ". $identity->locale_name); ?>



<?php $__env->startSection("page_css"); ?>
    <style type="text/css">
        @import  url(http://fonts.googleapis.com/css?family=Lato:400,700);

        body {
            font-family: 'Lato', 'sans-serif';
        }

        .profile {
            min-height: 355px;
            display: inline-block;
        }

        figcaption.ratings {
            margin-top: 20px;
        }

        figcaption.ratings a {
            color: #f1c40f;
            font-size: 11px;
        }

        figcaption.ratings a:hover {
            color: #f39c12;
            text-decoration: none;
        }

        .divider {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .emphasis {
            border-top: 4px solid transparent;
        }

        .emphasis:hover {
            border-top: 4px solid #1abc9c;
        }

        .emphasis h2 {
            margin-bottom: 0;
        }

        span.tags {
            background: #1abc9c;
            border-radius: 2px;
            color: #f5f5f5;
            font-weight: bold;
            padding: 2px 4px;
        }

        .dropdown-menu {
            background-color: #34495e;
            box-shadow: none;
            -webkit-box-shadow: none;
            width: 250px;
            margin-left: -125px;
            left: 50%;
        }

        .dropdown-menu .divider {
            background: none;
        }

        .dropdown-menu > li > a {
            color: #f5f5f5;
        }

        .dropup .dropdown-menu {
            margin-bottom: 10px;
        }

        .dropup .dropdown-menu:before {
            content: "";
            border-top: 10px solid #34495e;
            border-right: 10px solid transparent;
            border-left: 10px solid transparent;
            position: absolute;
            bottom: -10px;
            left: 50%;
            margin-left: -10px;
            z-index: 10;
        }
    </style>
<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8 col-lg-offset-3 col-lg-6">
                <div class="well profile">
                    <div class="col-sm-12">
                        <div class="col-xs-12 col-sm-6">
                            <h2><?php echo e($identity->locale_name); ?></h2>
                            <p><strong><?php echo e(trans('pages/users.identityType')); ?></strong> :
                                <?php echo e(trans("pages/users.$identity->user_type")); ?> </p>
                            <p><strong><?php echo e(trans('pages/users.title')); ?></strong> :
                                <?php echo e(trans("pages/users.$identity->user_title")); ?> </p>
                            <p><strong><?php echo e(trans('pages/users.email_address')); ?></strong> :
                                <?php echo e($identity->details->email); ?> </p>
                            <p><strong><?php echo e(trans('pages/users.identitySubscriptions')); ?>: </strong>
                                <?php if($identity->is_client): ?> <span class="label label-primary">
                                    <?php echo e(trans('pages/users.client')); ?></span><?php endif; ?>
                                <?php if($identity->is_vendor): ?><span class="label label-success">
                                    <?php echo e(trans('pages/users.vendor')); ?></span><?php endif; ?>
                                <?php if($identity->is_supplier): ?><span class="label label-warning">
                                    <?php echo e(trans('pages/users.supplier')); ?></span><?php endif; ?>
                            </p>
                            <p><strong><?php echo e(trans('pages/users.address')); ?></strong> :
                                <?php echo e($identity->details->address); ?> </p>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <p><strong><?php echo e(trans('pages/users.created_at')); ?></strong> :
                                <?php echo e($identity->created_at); ?> </p>
                            <p><strong><?php echo e(trans('pages/users.phone_number')); ?></strong> :
                                <?php echo e($identity->phone_number); ?> </p>
                            <p><strong><?php echo e(trans('pages/users.work_manager_name')); ?></strong> :
                                <?php echo e($identity->details->responsible_name); ?> </p>
                            <p><strong><?php echo e(trans('pages/users.work_phone_number')); ?></strong> :
                                <?php echo e($identity->details->responsible_phone_number); ?> </p>


                            <?php if($identity->is_vendor): ?>
                                <p><strong><?php echo e(trans('pages/users.vat_number')); ?></strong> :
                                    <?php echo e($identity->details->vat); ?> </p>
                                <p><strong><?php echo e(trans('pages/users.cr_number')); ?></strong> :
                                    <?php echo e($identity->details->cr); ?> </p>
                            <?php endif; ?>

                        </div>
                    </div>
                    <div class="col-xs-12 divider text-center">
                        <?php if($identity->is_client): ?>
                            <div class="col-xs-12 col-sm-6 emphasis">
                                <h2><strong><?php echo e(money_format("%i",$identity->balance)); ?> </strong></h2>
                                <p><small><?php echo e(trans('pages/users.client_balance')); ?></small></p>

                                <a href="<?php echo e(route('entities.user',[$clientAccount->id,$identity->id])); ?>">كشف
                                    حساب</a>
                            </div>
                        <?php endif; ?>
                        <?php if($identity->is_vendor): ?>
                            <div class="col-xs-12 col-sm-6 emphasis">
                                <h2><strong><?php echo e(money_format("%i",$identity->vendor_balance)); ?> </strong></h2>
                                <p><small><?php echo e(trans('pages/users.vendor_balance')); ?></small></p>
                                <a href="<?php echo e(route('entities.user',[$vendorAccount->id,$identity->id])); ?>">كشف
                                    حساب</a>
                            </div>
                        <?php endif; ?>


                    </div>
                    <?php if(!empty($identity->gateways)): ?>
                    <div class="col-xs-12 divider">
                        <h2><strong><?php echo e(trans('pages/users.gateways')); ?> </strong></h2>
                        <?php $__currentLoopData = $identity->gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><strong><?php echo e($gateway->bank->locale_name); ?> </strong> : <small>
                                    <?php echo e($gateway->detail); ?></small></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                        <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/identities/view.blade.php ENDPATH**/ ?>