<!DOCTYPE html>
<html>
<head>
    <title><?php echo e($invoice->invoice_number); ?></title>

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
            padding: 10px;
            padding-bottom: 0px !important;
            margin-bottom: 0px !important;
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
            /*font-family: Arial !important;*/
            font-family: 'El Messiri', sans-serif !important;


        }

        .background_primary {
            background-color: #777777;
            padding: 10px;
            margin: 2px;
            margin-bottom: 10px;

        }

        section table tbody.body .no, .total_numbers .number {
            background-color: #777777;
        }

        .company-info {
            margin-bottom: 5px;
        }


        .total_numbers {
            width: 200px;
            margin: 3px;
        }

        .total_numbers .label {
            padding: 5px;
            padding-top: 8px;
            text-align: right !important;
            line-height: 1em;
        }


        .total_numbers .value {
            line-height: 1em;
            padding: 5px;
            padding-top: 5px;
        }

        .issued_by {
            margin-left: 20px;
            text-align: right;
        }


        .total {
            background-color: white !important;
            color: black !important;
        }
    </style>


</head>

<body lang="ar" style="">
<div class="" style="margin:10px" id="app">
    <div class="row">

        <div class="col-md-6" style="float: left;padding-top: 10px;color: black !important;">
            <h5><?php echo e(__('pages/invoice.branch')); ?> :
                <?php echo e($invoice->branch->locale_name); ?>

            </h5>
            <h5 style="margin: 10px 0px !important;">الرقم الضريبي : <?php echo e(auth()->user()->organization->vat); ?></h5>

            <h5 style="margin: 10px 0px !important;"> السجل التجاري : <?php echo e(auth()->user()->organization->cr); ?></h5>
            <h5 style="margin: 10px 0px !important;"> رقم الهاتف : <?php echo e(auth()->user()->organization->phone_number); ?></h5>

        </div>
        <div class="col-md-6 text-right">
            <img src="<?php echo e(asset(auth()->user()->organization->logo)); ?>" class="logo">
        </div>
    </div>


    <div class="">
        <div class="row" style="color:black !important;font-weight: bolder !important;font-size: 19px">


            <div class="col-md-12 text-right" style="float: right">
                <div class="company-info" style="color: black !important; margin-top: -30px;">
                    <span style="padding-right: 13px;font-size: 25px"><?php echo e(auth()->user()->organization->title_ar); ?></span>
                    <p style="padding-right: 13px;font-size: 20px;margin-top:10px"><?php echo e(auth()->user()
                    ->organization->description_ar); ?></p>

                </div>
            </div>
        </div>

        <br>
        
        <div class="row background_primary">

            <div class="col-md-6 text-right" style="float: right">
                <div class="company-info" style="margin-bottom: 8px">
                    <span><?php echo e(__('pages/invoice.date')); ?> :
                        <span style="direction: ltr !important;
                    display: inline-block"><?php echo e($invoice->created_at); ?></span></span>
                </div>


                <div class="company-info" style="margin-bottom: 10px">
                    <span><?php echo e($invoice->user_type); ?> : <?php echo e($invoice->final_user_name); ?></span>
                </div>
                <div class="company-info">
                    <span><?php echo e(__('pages/invoice.phone_number')); ?> :  <?php echo e($invoice->user->phone_number); ?></span>
                </div>

            </div>


            <div class="col-md-6" style="float: left">

                <div class="company-info" style="margin-bottom: 10px">
                    <span><?php echo e(__('pages/invoice.invoice')); ?>

                        <?php echo e($invoice->description); ?> <?php echo e(__('pages/invoice.number')); ?> :
                        <?php echo e($invoice->invoice_number); ?></span>
                </div>
                <div class="company-info" style="margin-bottom: 10px">
                    <span><?php echo e($invoice->manager_type); ?> : <?php echo e($invoice->manager->name); ?></span>
                </div>
                
                
                
                <div class="company-info">
                    <span><?php echo e(__('pages/invoice.department')); ?> :  <?php echo e($invoice->department->locale_title); ?></span>
                </div>


            </div>

        </div>
    </div>


    <section>

        <div class="">
            <div class="table-wrapper">
                <table>
                    <tbody class="head" style="background-color: black !important;">
                    <tr>
                        <th class="no">#</th>
                        <th class="desc" style="width: 180px !important;background-color: #777777 !important;"><?php echo e(__
                        ('pages/invoice.item_name')); ?></th>
                        <th class="unit"
                            style="width: 30px !important;background-color: #777777 !important;">
                            <?php echo e(__('pages/invoice.qty')); ?></th>
                        <?php if($invoice->show_items_price_in_print_mode): ?>
                            <th class="unit"
                                style="width: 50px !important;background-color: #777777 !important;"><?php echo e(__('pages/invoice.price')); ?></th>
                            <th class="unit"
                                style="width: 70px !important;background-color: #777777 !important;"><?php echo e(__('pages/invoice.total')); ?> </th>
                            <th class="unit"
                                style="width: 40px !important;background-color: #777777 !important;">
                                <?php echo e(__('pages/invoice.discount')); ?> </th>
                            <th class="unit"
                                style="width: 40px !important;background-color: #777777 !important;">
                                <?php echo e(__('pages/invoice.tax')); ?> </th>
                            <th class="unit"
                                style="width: 70px !important;background-color: #777777 !important;">الاجمالي
                            </th>
                        <?php endif; ?>
                    </tr>
                    </tbody>
                    <tbody class="body">
				<?php $items_qty_count = 0; ?>
                    <?php if(!empty($invoice->items)): ?>
                        <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($item->belong_to_kit==false && $item->show_price_in_print_mode && $item->item != null): ?>
						   <?php $items_qty_count = $items_qty_count + $item->qty ?>
                                 <?php if($loop->index%2==0): ?>
							   <?php $background_color = "#ffffff"; ?>
                                      <tr>
                                 <?php else: ?>
                                     <tr style="background-color: #8888">
								  <?php $background_color = "#eee"; ?>
                                         <?php endif; ?>
                                         <td class="no" style="width:30px !important;"><?php echo e($loop->index + 1); ?></td>
                                         <td class="desc" style="width: 10%  !important;text-align: right !important;
                                                 font-weight: bold;font-size: 13px !important;color: black;background-color:
								 <?php echo $background_color;?> !important;"><?php echo e(mb_substr($item->item->locale_name, 0,55)); ?></td>
                                         <td class="total" style="background-color:
								 <?php echo $background_color;?> !important;"><?php echo e($item->qty); ?></td>
                                         <?php if($invoice->show_items_price_in_print_mode): ?>
                                             <td class="total" style="background-color:
									<?php echo $background_color;?> !important;"><?php echo e(roundMoney($item->price)); ?></td>
                                             <td class="total" style="background-color:
									<?php echo $background_color;?> !important;"> <?php echo e(roundMoney($item->total)); ?></td>
                                             <td class="total" style="background-color:
									<?php echo $background_color;?> !important;"> <?php echo e(roundMoney($item->discount)); ?></td>
                                             <td class="total" style="background-color:
									<?php echo $background_color;?> !important;"> <?php echo e(roundMoney($item->tax)); ?></td>
                                             <td class="total" style="background-color:
									<?php echo $background_color;?> !important;"> <?php echo e(roundMoney($item->net)); ?></td>
                                         <?php endif; ?>
                                     </tr>
                                     <?php if(!in_array($item->invoice_type,['purchase'])): ?>
                                         <?php if($item->item->is_need_serial): ?>
                                             <?php $__currentLoopData = $item->item->serials()
                                            ->where([
                                            ["sale_id",$invoice->id],
                                            ["item_id",$item->item->id],
                                            ])
                                            ->orWhere([["return_sale_id",$invoice->id],["item_id",$item->item->id]])
                                            ->orWhere([["return_purchase_id",$invoice->id],["item_id",$item->item->id]])
                                            ->orWhere([["purchase_id",$invoice->id],["item_id",$item->item->id]])
                                            ->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $serial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                 <tr style="background-color: #8888">

                                                     <td class="" style="width:30px !important;">S/N</td>
                                                     <td class="desc"
                                                         style="width: 10%  !important;text-align: right !important;
                                                                 font-weight: bold;font-size: 10px !important;color: black;
                                                                 background-color:
										       <?php echo $background_color;?> !important;padding-right: 20px !important;"><?php echo e($serial->serial); ?></td>
                                                     <td class="total" style="background-color:
										   <?php echo $background_color;?> !important;"></td>
                                                     <?php if($invoice->show_items_price_in_print_mode): ?>
                                                         <td class="total" style="background-color:
											  <?php echo $background_color;?> !important;"></td>
                                                         <td class="total" style="background-color:
											  <?php echo $background_color;?> !important;"></td>
                                                         <td class="total" style="background-color:
											  <?php echo $background_color;?> !important;"></td>
                                                         <td class="total" style="background-color:
											  <?php echo $background_color;?> !important;"></td>
                                                         <td class="total" style="background-color:
											  <?php echo $background_color;?> !important;"></td>
                                                     <?php endif; ?>
                                                 </tr>

                                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                         <?php endif; ?>
                                     <?php endif; ?>
                                 <?php endif; ?>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            <?php endif; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </section>
    <div class="">
        <?php $show_price_in_print_mode_tax_net = $invoice->show_price_in_print_mode_tax_and_net;?>
        <div class="total_numbers text-right">
            <div class="">
                <h4 style="color: white;font-size: 22px;padding: 4px"><?php echo e(__('pages/invoice.invoice_data')); ?></h4>
            </div>

            <div class="number">
                <div class="label"><?php echo e(__('pages/invoice.total')); ?> :</div>
                <div class="value"> <?php echo e(roundMoney($invoice->total)); ?></div>
                <div class="clear"></div>
            </div>
            <div class="number">
                <div class="label"><?php echo e(__('pages/invoice.discount')); ?> :</div>
                <div class="value"><?php echo e(roundMoney($invoice->discount)); ?></div>
                <div class="clear"></div>
            </div>

            <div class="number">
                <div class="label"><?php echo e(__('pages/invoice.subtotal')); ?> :</div>
                <div class="value"><?php echo e(roundMoney( $invoice->subtotal)); ?></div>
                <div
                        class="clear"></div>
            </div>

            <div class="number">
                <div class="label"><?php echo e(__('pages/invoice.vat')); ?> :
                </div>
                <div class="value"> <?php echo e(roundMoney($invoice->tax)); ?></div>
                
                <div class="clear"></div>
            </div>
            <div class="number">
                <div style="text-align: center;">الاجمالي</div>
                <div style="margin: 10px;text-align: center;font-size: 25px">
                    <h3 class=><b>
                            <?php echo e(roundMoney($invoice->net)); ?></b></h3>
                </div>
                <div class="clear"></div>
            </div>

        </div>


    </div>

    <div class="total_numbers text-right " style="float:right;color: black !important;">
        <div class="">
            <h4 style="color: white;font-size: 22px;padding: 4px"><?php echo e(__('pages/invoice.invoice_data')); ?></h4>
        </div>

        <div class="number">
            <div class="label"><?php echo e(__('pages/invoice.items_count')); ?> :</div>
            <div class="value"> <?php echo e($invoice->items->count()); ?></div>
            <div class="clear"></div>
        </div>
        <div class="number">
            <div class="label"><?php echo e(__('pages/invoice.items_qty_count')); ?> :</div>
            <div class="value"><?php echo e($items_qty_count); ?></div>
            <div class="clear"></div>
        </div>


        <div class="">
            <?php if($invoice->notes!=""): ?>
                <div class="">
                    <h2 style="font-size: 23px;
    margin-top: 19px;
    margin-bottom: 5px;">ملاحظات:</h2>
                    <?php echo e(mb_substr($invoice->notes,0,255)); ?>

                </div>
            <?php endif; ?>
            <div class="" style="margin-top: 10px">
                <h3 style="font-weight: bolder;margin-top: 5px;margin-bottom: 5px">الشروط والاحكام</h3>
                <p>* البضاعة المباعة لاترد ولا تستبدل بعد فتحها .</p>
                <p>* الارجاع خلال ثلاثة أيام .</p>
                <p>* التبديل خلال سبعة أيام .</p>

                <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($item->belong_to_kit==false &&  $item->item != null && $item->show_price_in_print_mode && $item->item->warranty): ?>
                        <p>* الصنف <?php echo e($loop->index + 1); ?> <?php echo e($item->item->warranty_title); ?> .</p>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            </div>
        </div>

    </div>


</div>


<img src="<?php echo e($invoice->getBackgroundAssetAttribute()); ?>">

<footer style="">
    <div class="">


        <div class="row" style="color: black !important;">
            <div class="stamp">

                <div id="barcode_demo"></div>


            </div>
            <div class="issued_by">
                <h3 style="margin-bottom: 9px"><?php echo e(__('reusable.issued_by')); ?></h3>
                <p style="margin-bottom: 2px"><?php echo e($invoice->creator->locale_name); ?></p>


            </div>
            <div class="clear"></div>
        </div>
        <div class="end" style="padding-top: 10px"> <?php echo e(auth()->user()->organization->city_ar); ?>

            - <?php echo e(auth()->user()->organization->address_ar); ?></div>
        <div style="padding-top: 10px;text-align: center"> سعدنا بخدمتك</div>
    </div>
</footer>

</body>

</html>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="<?php echo e(asset('accounting/js/jquery-barcode.min.js')); ?>"></script>
<script>
    $("#barcode_demo").barcode(
        "<?php echo e($invoice->title); ?>",// Value barcode (dependent on the type of barcode)
        "code39" // type (string)
    );
    print();
</script>
<?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/printer/a4.blade.php ENDPATH**/ ?>