<?php $__env->startSection('title',$account->locale_name); ?>











<?php $__env->startSection('content'); ?>



    <div class="card">


        <div class="table-container">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center " colspan="2">المجاميع</th>
                    <th class="text-center " colspan="2">الارصدة</th>
                </tr>
                <tr>
                    <th class="text-center "> التاريخ</th>
                    <th class="text-center "> رقم القيد</th>
                    <th class="text-center "> الهوية</th>
                    <th class="text-center ">البيان</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                </tr>
                </thead>

                <tbody>
			 <?php
			 $total_credit = 0;
			 $total_debit = 0;

			 $total_balance = 0;
			 ?>
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php if($transaction['is_transaction']): ?>


                        <?php if($transaction['type']=='credit'): ?>

					    <?php $total_balance = $total_balance - $transaction['amount'];?>
                             <tr>
                                 <th class="text-center "><?php echo e($transaction->created_at); ?></th>
                                 <th class="text-center "><?php echo e($transaction->container_id); ?></th>
                                 <th class="text-center ">
                                     <?php if(!empty($transaction->user)): ?>
                                         <a href="<?php echo e(route('management.users.show',$transaction->user->id)); ?>"><?php echo e($transaction->user->name); ?></a>
                                     <?php else: ?>
                                         -
                                     <?php endif; ?>
                                 </th>
                                 <th class="text-center ">
                                     <?php if($transaction->invoice_id>=1): ?>
                                         <?php if(in_array($transaction->invoice->invoice_type,['sale','r_sale'])): ?>
                                             <a href="<?php echo e(route('management.sales.show',$transaction->invoice->sale->id )); ?>"><?php echo e($transaction->invoice->title); ?></a>
                                         <?php else: ?>
                                             <a href="<?php echo e(route('management.purchases.show',
                                             $transaction->invoice->purchase->id)); ?>"> <?php echo e($transaction->invoice->title); ?></a>
                                         <?php endif; ?>


                                     <?php else: ?>
                                         
                                         


                                         <a href="<?php echo e(route('management.transactions.show', $transaction->container_id)); ?>"><?php echo e($transaction->container_id); ?></a>
                                     <?php endif; ?>
                                 </th>
                                 <th class="text-center ">0</th>
                                 <th class="text-center "><?php echo e(money_format("%i",$transaction->amount)); ?></th>
                                 <?php if($account->type=='debit'): ?>
                                     <?php if($total_balance<0): ?>
                                         <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                         <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>
                                     <?php else: ?>
                                         <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>
                                         <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                     <?php endif; ?>
                                 <?php else: ?>

                                     <?php if($total_balance<0): ?>
                                         <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>
                                         <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                     <?php else: ?>
                                         <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                         <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>

                                     <?php endif; ?>

                                 <?php endif; ?>

                             </tr>
                        <?php else: ?>
					    <?php $total_balance = $total_balance + $transaction['amount'];?>
                             <tr>
                                 <th class="text-center "><?php echo e($transaction->created_at); ?></th>
                                 <th class="text-center "><?php echo e($transaction->id); ?></th>
                                 <th class="text-center ">
                                     <?php if(!empty($transaction->user)): ?>
                                         <a href="<?php echo e(route('management.users.show',$transaction->user->id)); ?>"><?php echo e($transaction->user->name); ?></a>
                                     <?php else: ?>
                                         -
                                     <?php endif; ?>
                                 </th>
                                 <th class="text-center ">
                                     <?php if($transaction->invoice_id>=1): ?>
                                         <?php if(in_array($transaction->invoice->invoice_type,['sale','r_sale'])): ?>
                                             <a href="<?php echo e(route('management.sales.show',
                                             $transaction->invoice->sale->id )); ?>"><?php echo e($transaction->invoice->title); ?></a>
                                         <?php else: ?>
                                             <a href="<?php echo e(route('management.purchases.show',
                                             $transaction->invoice->purchase->id)); ?>"> <?php echo e($transaction->invoice->title); ?></a>
                                         <?php endif; ?>


                                     <?php else: ?>
                                         <a href="<?php echo e(route('management.transactions.show', $transaction->container_id)); ?>"><?php echo e($transaction->container_id); ?></a>

                                     <?php endif; ?>
                                 </th>
                                 <th class="text-center "><?php echo e(money_format("%i",$transaction->amount )); ?></th>
                                 <th class="text-center ">0</th>
                                 <?php if($account->type=='debit'): ?>
                                     <?php if($total_balance<0): ?>
                                         <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                         <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>
                                     <?php else: ?>
                                         <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>
                                         <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                     <?php endif; ?>
                                 <?php else: ?>

                                     <?php if($total_balance<0): ?>
                                         <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>
                                         <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                     <?php else: ?>
                                         <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                         <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>

                                     <?php endif; ?>

                                 <?php endif; ?>

                             </tr>
                        <?php endif; ?>





                    <?php else: ?>

					<?php $total_balance = $total_balance + +$transaction['debit'] - $transaction['credit'];?>
                         <tr>
                             <th class="text-center "><?php echo e($transaction->created_at); ?></th>
                             <th class="text-center "><?php echo e($transaction->id); ?></th>
                             <th class="text-center ">
                                 -
                             </th>
                             <th class="text-center "><a href="<?php echo e(route('management.accounts.show',
                                $transaction->id)); ?>"><?php echo e($transaction->locale_name); ?></a></th>
                             <th class="text-center "><?php echo e(money_format("%i",$transaction->debit )); ?></th>
                             <th class="text-center "><?php echo e(money_format("%i",$transaction->credit )); ?></th>
                             <?php if($account->type=='debit'): ?>
                                 <?php if($total_balance<0): ?>
                                     <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                     <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>
                                 <?php else: ?>
                                     <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>
                                     <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                 <?php endif; ?>
                             <?php else: ?>

                                 <?php if($total_balance<0): ?>
                                     <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>
                                     <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                 <?php else: ?>
                                     <th class="text-center "><?php echo e(money_format("%i",0)); ?></th>
                                     <th class="text-center "><?php echo e(money_format("%i",abs($total_balance))); ?></th>

                                 <?php endif; ?>

                             <?php endif; ?>

                         </tr>

                    <?php endif; ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounts/transactions.blade.php ENDPATH**/ ?>