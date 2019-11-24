<?php $__env->startSection('title',$account->name); ?>











<?php $__env->startSection('content'); ?>


    <div class="box">
        <div class="card-body">

        </div>
    </div>

    <div class="card">


        <div class="table-container">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center ">التاريخ والوقت</th>
                    <th class="text-center ">الرقم</th>
                    <th class="text-center ">الهوية</th>
                    <th class="text-center ">بيان</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">رصيد</th>
                </tr>
                </thead>

                <tbody>
			 <?php
			 $total_credit = 0;
			 $total_debit = 0;
			 ?>
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php if($transaction['is_transaction']): ?>


                        <tr>
                            <th class="text-center "><?php echo e($transaction->created_at); ?></th>
                            <th class="text-center "><?php echo e($transaction->id); ?></th>
                            <th class="text-center ">-</th>
                            <th class="text-center "><<?php echo e($transaction); ?></th>
                            <th class="text-center "><?php echo e(money_format("%i",$transaction->amount )); ?></th>
                            <th class="text-center "><?php echo e(money_format("%i",$transaction->amount)); ?></th>


                        </tr>


                    <?php endif; ?>


                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/accounts/transactions.blade.php ENDPATH**/ ?>