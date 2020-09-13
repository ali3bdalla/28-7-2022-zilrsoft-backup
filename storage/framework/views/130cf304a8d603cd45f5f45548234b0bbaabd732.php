<?php $__env->startSection('title',__('pages/invoice.return')); ?>


<?php $__env->startSection('page_css'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/invoice'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>

    <accounting-sales-return-component
            :gateways='<?php echo json_encode($gateways, 15, 512) ?>'
            :creator='<?php echo json_encode($invoice->creator, 15, 512) ?>'
            :items='<?php echo json_encode($items, 15, 512) ?>'
            :invoice='<?php echo json_encode($invoice->load('department', 'branch'), 512) ?>'
            :sale='<?php echo json_encode($sale->load('client', 'salesman'), 512) ?>'>

    </accounting-sales-return-component>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/sales/edit.blade.php ENDPATH**/ ?>