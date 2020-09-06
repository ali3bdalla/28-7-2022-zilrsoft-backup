<?php $__env->startSection('title',$account->locale_name); ?>





<?php $__env->startSection('content'); ?>



    <div class="panel">

        <div class="panel-heading">

        </div>

        <div class="panel-body">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center " colspan="2">المجاميع</th>
                    <th class="text-center " colspan="2">الرصيد</th>
                </tr>
                <tr>
                    <th class="text-center ">التاريخ والوقت</th>
                    <th class="text-center ">رقم</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                </tr>
                </thead>

                <tbody>


                <?php $accumulation_total = 0;?>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th class="text-center "><?php echo e($user['id']); ?></th>
                        <?php if($account->slug=='clients'): ?>

		                    <?php
                              $client_transactions_amount = $user->cleintTransactionsAmount($account);
		                     $accumulation_total+= $client_transactions_amount;
		                    ?>
                            <th class="text-center "><a
                                        href="<?php echo e(route('accounting.accounts.client',[ $user['id'],$account->id] )); ?>"><?php echo e($user['locale_name']); ?></a></th>


                            <th class="text-center "><?php echo e(money_format("%i",$account->debit_transaction()->where('user_id',$user['id'])->sum('amount'))); ?></th>
                            <th class="text-center "><?php echo e(money_format("%i",$account->credit_transaction()->where('user_id', $user['id'])->sum('amount'))); ?></th>
                            

                            <th class="text-center "><?php echo e($accumulation_total > 0  ? money_format("%i",
                            $accumulation_total) : 0); ?></th>

                            <th class="text-center "><?php echo e($accumulation_total < 0  ? money_format("%i",
                            abs($accumulation_total)) : 0); ?></th>

                        <?php else: ?>

						<?php $vendor_transactions_amount = $user->vendorTransactionsAmount($account);
		                    $accumulation_total+= $vendor_transactions_amount;
						?>
                            <th class="text-center "><a
                                        href="<?php echo e(route('accounting.accounts.vendor',[ $user['id'],$account->id] )); ?>"><?php echo e($user['locale_name']); ?></a></th>
                            <th class="text-center "><?php echo e(money_format("%i",$account->debit_transaction()->where('user_id',$user['id'])->sum('amount'))); ?></th>
                            <th class="text-center "><?php echo e(money_format("%i",$account->credit_transaction()->where('user_id', $user['id'])->sum('amount'))); ?></th>
                            
                            <th class="text-center "><?php echo e($accumulation_total < 0  ? money_format("%i",
                            abs($accumulation_total)) : 0); ?></th>

                            <th class="text-center "><?php echo e($accumulation_total > 0  ? money_format("%i",
                            $accumulation_total) : 0); ?></th>

                        <?php endif; ?>


                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>

                <?php echo e($users->links()); ?>

            </table>
        </div>

    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/charts/transactions/identity.blade.php ENDPATH**/ ?>