<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css" rel="stylesheet"
      id="bootstrap-css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=El+Messiri&display=swap" rel="stylesheet">
<style>


    .invoice-title h2, .invoice-title h3 {
        display: inline-block;
    }

    .table > tbody > tr > .no-line {
        border-top: none;
    }

    .table > thead > tr > .no-line {
        border-bottom: none;
    }

    .table > tbody > tr > .thick-line {
        border-top: 2px solid;
    }

    * {
        /*font-family: 'El Messiri', sans-serif !important;*/


    }

    .header_title span {
        font-size: 18px;
    }

    .total_header {
        font-size: 20px;
    }


</style>


<div class="" id="container">
    <div class="raw">

        <div class="col-xs-12">
            <div class="header_title">
                <h3 align="center"><?php echo e($invoice->organization->title_ar); ?></h3>
                <h4 align="center">القيمة المضافة : <?php echo e($invoice->organization->vat); ?></h4>

            </div>


        </div>

    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3 class="pull-right"> فاتورة</h3>
            <h3 class="pull-left"> <?php echo e($invoice->title); ?></h3>
        </div>
    </div>

    
    
    
    
    
    

    
    
    
    
    

    <div class="row header_title">
        <div class="col-xs-6"> العميل</div>
        <div class="col-xs-6 text-left"> <?php echo e($invoice->sale->client->name); ?></div>
    </div>


    <div class="row header_title">
        <div class="col-xs-6"> التاريخ</div>
        <div class="col-xs-6 text-left"> <?php echo e($invoice->created_at); ?></div>
    </div>


    
    
    
    
    
    


    
    
    
    
    
    

    <div class="row">

        <div class="panel-body">
            <div class="">
                <table class="table">
                    <thead>
                    <tr>
                        <td><strong>الصنف</strong></td>
                        <td class="text-left"><strong>السعر</strong></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php echo e($item->item->ar_name); ?><br>
                                <?php echo e($item->item->barcode); ?>

                            </td>
                            <td class="text-left">
                                الكمية : <?php echo e($item->qty); ?><br>
                                <?php echo e(money_format('%i',$item->qty * $item->price)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2">

        </div>
        <div class="col-xs-10">

            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> الاجمالي</span>
                    <span class="pull-left">  <?php echo e(money_format('%i ريال',$invoice->total)); ?></span>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> الخصم</span>
                    <span class="pull-left"><?php echo e(money_format('%i ريال',$invoice->discount)); ?></span>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> الضريبة (5%)</span>
                    <span class="pull-left"> <?php echo e(money_format('%i ريال',$invoice->tax)); ?></span>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> الصافي</span>
                    <span class="pull-left">  <?php echo e(money_format('%i ريال',$invoice->subtotal)); ?></span>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> المدفوع</span>
                    <span class="pull-left"> <strong> <?php echo e(money_format('%i ريال',$invoice->net - $invoice->remaining)); ?></strong></span>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> المبتقي</span>
                    <span class="pull-left">  <?php echo e(money_format('%i ريال',$invoice->remaining)); ?></span>
                </div>
            </div>

        </div>


    </div>

    <hr>
    <div class="row">
        <div class="col-xs-12">
            <div class="text-center">
                <span class="header_title total_header"><?php echo e($invoice->organization->city_ar); ?> - <?php echo e($invoice->organization->address_ar); ?></span>
            </div>
        </div>
        
        

        
        

        
        
        
    </div>
    <div class="row">

        <div class="col-xs-12">
            <div class="text-center">
                <span class="header_title total_header">نراكم قريبا</span>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /usr/local/var/www/resources/views/template/receipt/pos.blade.php ENDPATH**/ ?>