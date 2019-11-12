<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        /*border-top: 2px solid;*/
    }

    * {
        /*font-family: 'El Messiri', sans-serif !important;*/
        font-size: 12px !important;
        font-weight: bold !important;

    }

    .header_title span {
        font-size: 18px;
    }

    .total_header {
        font-size: 20px;
    }

    tbody {
        border-bottom: 2px solid black !important;

    }


</style>


<div class="" id="container">

    <div class="raw">

        <div class="col-xs-12">
            
            <div align="center" style="font-size: 27px !important;"><?php echo e($invoice->organization->title_ar); ?></div>
            
            

        </div>

    </div>

    <div class="row">
        <div class="col-sm-12 text-center">
            رقم الفاتورة
        </div>
        <div class="col-xs-12 text-center" style="font-size: 35px !important;">
            <?php echo e($invoice->title); ?>

        </div>
    </div>

    <div class="row">
        <div class="col-xs-6"> اسم العميل</div>
        <div class="col-xs-6 text-left"> <?php echo e($invoice->sale->client->name); ?></div>
    </div>


    <div class="row">
        <div class="col-xs-6"> التاريخ</div>
        <div class="col-xs-6 text-left " style="direction: ltr"> <?php echo e($invoice->created_at); ?></div>
    </div>


    <div class="row ">
        <div class="col-xs-6"> الفرع</div>
        <div class="col-xs-6 text-left " style="direction: ltr"> <?php echo e($invoice->creator->branch->name); ?></div>
    </div>


    <div class="row ">
        <div class="col-xs-6"> القسم</div>
        <div class="col-xs-6 text-left " style="direction: ltr"> <?php echo e($invoice->creator->department->title); ?></div>
    </div>


    <div class="row">
        <div class="col-xs-6"> البائع</div>
        <div class="col-xs-6 text-left " style="direction: ltr"> <?php echo e($invoice->sale->salesman->name); ?></div>
    </div>

    <br>

    
    
    
    
    
    


    
    
    
    
    
    

    <div class="row">

        <div class="col-xs-12">
            <table class="table" style="margin-right: 3px;border:none !important;">
                <thead>
                
                
                

                
                
                
                
                
                
                
                
                
                </thead>

                <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tbody>


                    <tr style="">

                        <td colspan="6">

                            <span><?php echo e($item->item
                        ->locale_name); ?></span><br><span><?php echo e($item->item->barcode); ?></span></td>
                    </tr>
                    <tr>
                        <td style="margin-right: -2px;"><span>الكمية</span></td>
                        <td><span>السعر</span></td>
                        <td><span>المجموع</span></td>
                        <td><span>الخصم</span></td>
                        
                        <td><span>الضريبة</span></td>
                        <td><span>النهائي</span></td>
                    </tr>
                    <tr>
                        <td style="padding-right:10px;"><span><?php echo e($item->qty); ?></span></td>
                        <td><span><?php echo e($item->price); ?></span></td>
                        <td><span><?php echo e($item->total); ?></span></td>
                        <td><span><?php echo e($item->discount); ?></span></td>
                        
                        <td><span><?php echo e($item->tax); ?></span></td>
                        <td><span><?php echo e($item->net); ?></span></td>
                    </tr>

                    
                    
                    
                    
                    </tbody>


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </table>
        </div>
    </div>
    <div class="row">


        <div class="col-xs-12">
            <div class="header_title">
                <span class="pull-right"> الاجمالي</span>
                <span class="pull-left">  <?php echo e(money_format('%i ريال',$invoice->total)); ?></span>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="header_title">
                <span class="pull-right"> الخصم</span>
                <span class="pull-left"><?php echo e(money_format('%i ريال',$invoice->discount_value)); ?></span>
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
                <span class="pull-right"> الضريبة </span>
                <span class="pull-left"> <?php echo e(money_format('%i ريال',$invoice->tax)); ?></span>
            </div>
        </div>


        <div class="col-xs-12">
            <div class="header_title">
                <span class="pull-right"> النهائى</span>
                <span class="pull-left"> <span> <?php echo e(money_format('%i ريال',$invoice->net - $invoice->remaining)); ?></span></span>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="header_title">
                <span class="pull-right"> المبتقي</span>
                <span class="pull-left">  <?php echo e(money_format('%i ريال',$invoice->remaining)); ?></span>
            </div>
        </div>


    </div>

    <hr style="border:1px solid #777">
    <div class="row">
        <div class="col-xs-12">
            <div class="text-center">
                <span class="header_title total_header"><?php echo e($invoice->organization->city_ar); ?> - <?php echo e($invoice->organization->address_ar); ?> - <?php echo e($invoice->organization->phone_number); ?></span>
            </div>
        </div>
        
        

        
        

        
        
        
    </div>
    <div class="row">


        <div class="col-xs-12">
            <div align="center" style="font-size: 13px !important;"> الرقم الضريبي : <?php echo e($invoice->organization->vat); ?></div>

            <p align="center" style="font-size: 13px !important;margin-top: 0px !important;"> السجل التجاري :
                <?php echo e($invoice->organization->cr); ?></p>
        </div>
        <div class="col-xs-12">
            <div class="text-center">
                <span class="header_title total_header">سعدنا بخدمتكم</span>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /usr/local/var/www/resources/views/template/receipt/pos2.blade.php ENDPATH**/ ?>