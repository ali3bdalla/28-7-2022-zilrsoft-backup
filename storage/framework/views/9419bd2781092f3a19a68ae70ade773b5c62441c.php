<table class="table table-bordered text-center">
    <thead>
    <tr>
        <th class="text-center ">التاريخ</th>
        <th class="text-center "></th>
        <th class="text-center "></th>
        <th class="text-center " colspan="2">المجاميع</th>

        <th class="text-center " colspan="2">الرصيد</th>
    </tr>
    <tr>
        <th class="text-center ">التاريخ والوقت</th>
        <th class="text-center ">رقم القيد</th>
        <th class="text-center ">السند</th>
        <th class="text-center ">مدين</th>
        <th class="text-center ">دائن</th>
        <th class="text-center ">مدين</th>
        <th class="text-center ">دائن</th>
    </tr>
    </thead>
    <tbody> 
        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <th class="text-center "><?php echo e($transaction->created_at); ?></th>
            <th class="text-center "><?php echo e($transaction->container_id); ?></th>
            <th class="text-center "><?php echo e($transaction->description); ?></th>
            <?php if(): ?>
            <th class="text-center "><?php echo e($transaction->amount); ?></th>
            <th class="text-center ">دائن</th>
            <th class="text-center ">مدين</th>
            <th class="text-center ">دائن</th>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>


<?php echo e($transactions->links()); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/charts/transactions/transactions.blade.php ENDPATH**/ ?>