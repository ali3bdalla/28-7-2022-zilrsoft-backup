<?php $__env->startSection('title',$chart->name); ?>


<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = `<?php echo json_encode(trans('pages/items'), 15, 512) ?>`
    </script>
<?php $__env->stopSection(); ?>










<?php $__env->startSection('content'); ?>


    <div class="">
        <item-accounting-cost-history-component  :activities='<?php echo json_encode($activities, 15, 512) ?>
                '></item-accounting-cost-history-component>
    </div>

<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/accounts/single_item_histories.blade.php ENDPATH**/ ?>