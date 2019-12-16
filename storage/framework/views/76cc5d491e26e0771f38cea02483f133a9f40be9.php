<!DOCTYPE html>
<html>
<head>
    <title><?php echo e(auth()->user()->organization->title_ar); ?></title>

    <!-- Latest compiled and minified CSS -->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,700&subset=latin,latin-ext'
          rel='stylesheet' type='text/css'>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="content-type" content="text-html; charset=utf-8">
    <link rel="stylesheet" href="<?php echo e(asset('template/css/bootstrap.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('template/css/pdf-rtl.css')); ?>">

    <link href="https://fonts.googleapis.com/css?family=El+Messiri&display=swap" rel="stylesheet">

    


    <style>
        body {
            margin: 10px;
        }

        tr th, tr td {
            padding: 4px !important;
            /*font-size: 16px !important;*/
            /*font-weight: bold !important;*/
            text-align: center !important;

        }

        
        
        
        
        
        


        . {
            padding: 10px
        }

        .company-info {
            color: white !important;
        }

        * {
            font-family: 'El Messiri', sans-serif !important;


        }

        .background_primary {
            background-color: #0859a4;
            padding: 10px;
            margin-bottom: 10px;
        }

        .company-info {
            margin-bottom: 5px;
        }


        .total_numbers .label {
            text-align: right !important;
        }

        .row {
            margin-top: 15px !important;
        }


        .directionfordate {
            direction: rtl !important;
        }

        /*.col-md-6 {*/
        /*      float: rig !important;*/
        /*  }*/


        .columns {
            font-size: 22px !important;
        }


    </style>


</head>

<body lang="ar" style="">
<div style="position: absolute;width: 100%;height: 100%;z-index: 100">

</div>
<div class="">

    
    <div class="row">

        <div class="col-md-6">

        </div>
        <div class="col-md-6 text-right">
            <img src="<?php echo e(asset(auth()->user()->organization->logo)); ?>" class="logo">
        </div>
    </div>


    <div class="background_primary">
        <div class="row">

            <div class="col-md-6" style="float: left">
                <h3 style="color: white"><?php if($payment->payment_type=='receipt'): ?><?php echo e(__('pages/payments.receipt')); ?><?php else: ?><?php echo e(__('pages/payments.payment')); ?><?php endif; ?>
                    <?php echo e(__('pages/invoice.number')); ?>

                    <?php echo e($payment->id); ?>

                </h3>
            </div>
            <div class="col-md-6 text-right" style="float: right">
                <div class="company-info">
                    <span><?php echo e(auth()->user()->organization->title_ar); ?></span>
                    <span class="line"> | </span>
                    <span><?php echo e(auth()->user()->organization->vat); ?></span>
                    <span class="line"> | </span>
                    <span><?php echo e(auth()->user()->organization->phone_number); ?></span><br>
                    

                </div>
            </div>
        </div>

        <hr>
        <div class="row">

            <div class="col-md-6" style="float: right">

                <div class="company-info">
                    <span><?php echo e(__('reusable.creator')); ?> : <?php echo e($payment->creator->name); ?></span>
                </div>
                <div class="company-info">
                    <span><?php echo e(__('pages/invoice.phone_number')); ?> : <?php echo e($payment->creator->user->phone_number); ?></span>
                </div>
                <div class="company-info">
                    <span><?php echo e(__('pages/invoice.department')); ?> :  <?php echo e($payment->creator->department->title); ?></span>
                </div>


            </div>
            <div class="col-md-6 text-right" style="float: left">
                <div class="company-info" style="direction: ltr">
                    <span><?php echo e(__('reusable.date')); ?> :<span class="directionfordate"> <?php echo e($payment->created_at); ?></span>
                    </span>
                </div>
                <div class="company-info">
                    <span><?php echo e($payment->steakholder_type); ?> : <?php echo e($payment->steakholder_name); ?></span>
                </div>
                <div class="company-info">
                    <span><?php echo e(__('pages/invoice.phone_number')); ?> :  <?php echo e($payment->steakholder_phone_number); ?></span>
                </div>

            </div>
        </div>
    </div>


    <div class="content" style="padding: 22px;font-size: 22px">
        <div class="row">

            <div class="col-md-6">

            </div>
            <div class="col-md-6">
                <h5><?php echo e(__('pages/payments.payment_start_title_' . $payment->payment_type)); ?><?php echo e(__('pages/payments.voucher_user_' . $payment->user->user_title)); ?>


                    <?php echo e($payment->user->name); ?>

                </h5>
            </div>
        </div>


        <div class="row">

            <div class="col-md-6"></div>

            <div class="col-md-6">
                <h5><?php echo e(__('pages/payments.amount_equ')); ?> <?php echo e($payment->amount); ?>

                    ريال</h5>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6"></div>
            <div class="col-md-6">
                <h5><?php echo e(__('pages/payments.amount_in_words')); ?> <?php echo e($payment->amount_ar_words); ?> </h5>
            </div>

        </div>


        <div class="row">

            <div class="col-md-6"></div>
            <div class="col-md-6">


                
                
                
                
                


                

                
                
                
                
                
                
                
                
            </div>
        </div>
        

        
        
        

        

        
        
        
        <hr>


        <?php if(!empty($payment->paymentable->locale_name)): ?>
            <div class="row">

                <div class="col-md-12">
                    <h5><?php echo e(__('pages/payments.paid_by_' . $payment->payment_type)); ?>

                        : <?php echo e($payment->paymentable->locale_name); ?>

                        
                        
                        
                    </h5>
                </div>
            </div>

        <?php endif; ?>

        <?php if($payment->slug=='transfer' && $payment->payment_type=='payment'): ?>
            <div class="row">
                <div class="col-md-12">
                    <h5><?php echo e(__('pages/payments.user_account')); ?>

                        : <?php echo e($payment->user_gateway->locale_name); ?></h5>
                </div>
            </div>


        <?php endif; ?>

        <?php if($payment->description!=''): ?>
            <div class="row">
                <div class="col-md-12">
                    <h5><?php echo e(__('pages/payments.payment_description')); ?>

                        : <?php echo e($payment->description); ?></h5>
                </div>
            </div>


        <?php endif; ?>

    </div>


</div>

<footer>
    <div class="">
        <div class="end"> <?php echo e(auth()->user()->organization->title); ?></div>
        <div class="text-center">
            <div class="col-xs-12">
                <div class="text-center">
                <span class="header_title total_header"><?php echo e(auth()->user()->organization->city_ar); ?> - <?php echo e(auth()->user()->organization->address_ar); ?></span>
                </div>
            </div>
        </div>

        <div class="text-center"> <?php echo e(auth()->user()->organization->vat); ?></div>
    </div>
</footer>

</div>
</body>

</html>


<script>
    // print();
</script><?php /**PATH /usr/local/var/www/resources/views/template/a4/voucher.blade.php ENDPATH**/ ?>