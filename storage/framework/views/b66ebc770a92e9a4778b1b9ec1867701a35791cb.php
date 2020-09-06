<?php $__env->startSection('title',$account->locale_name); ?>











<?php $__env->startSection('content'); ?>



    <div class="panel">

        <div class="panel-heading">

        </div>

        <div class="panel-body">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center ">التاريخ</th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
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
			 
			 
			 <?php $balance = 0;?>
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <tr>
                        <th class="text-center "><?php echo e($transaction->created_at); ?></th>
                        <th class="text-center "><a href="<?php echo e(route('accounting.transactions.show',
                        $transaction->container_id)); ?>"><?php echo e($transaction->container_id); ?></a></th>
                        <th class="text-center ">
                            <?php if($transaction->invoice_id>=1): ?>
                                <a href="<?php echo e(route('accounting.sales.show',
                        $transaction->invoice->id)); ?>"><?php echo e($transaction->invoice->title); ?></a>

                            <?php else: ?>

                                <a href="<?php echo e(route('accounting.transactions.show',
                        $transaction->container_id)); ?>"><?php echo e($transaction->container_id); ?></a>

                            <?php endif; ?>
                        </th>
                        <?php if($transaction['creditable_type']==""): ?>
						<?php $balance = $balance + $transaction['amount'];?>
                            <th class="text-center "><?php echo e(money_format("%i",$transaction->amount)); ?></th>
                            <th class="text-center ">0</th>
                        <?php else: ?>
						<?php $balance = $balance - $transaction['amount'];?>
                            <th class="text-center ">0</th>
                            <th class="text-center "><?php echo e(money_format("%i",$transaction->amount)); ?></th>
                        <?php endif; ?>
                        <th class="text-center ">
                            <?php if($balance>0): ?>

                                <?php echo e(money_format("%i",$balance)); ?>

                            <?php else: ?>
                                0
                            <?php endif; ?>
                        </th>
                        <th class="text-center ">
                            <?php if($balance<0): ?>

                                <?php echo e(money_format("%i",abs($balance))); ?>

                            <?php else: ?>
                                0
                            <?php endif; ?>

                        </th>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/charts/transactions/client.blade.php ENDPATH**/ ?>