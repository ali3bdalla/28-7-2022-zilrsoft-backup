<?php $__env->startSection('title',$account->locale_name); ?>





<?php $__env->startSection('content'); ?>



    <div class="card">


        <div class="table-container">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center " colspan="2">المجاميع</th>
                    <th class="text-center " colspan="2">الارصدة</th>
                </tr>
                <tr>
                    <th class="text-center "> #</th>
                    <th class="text-center ">الرقم</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                    <th class="text-center ">مدين</th>
                    <th class="text-center ">دائن</th>
                </tr>
                </thead>

                <tbody>

                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                    <tr>
                        <th class="text-center "><?php echo e($item['id']); ?></th>
                        <?php if($account->slug=='clients'): ?>
                            <th class="text-center "><a
                                        href="<?php echo e(route('management.accounts.client',[ $item['id'],$account->id] )); ?>"><?php echo e($item['locale_name']); ?></a></th>

                        <?php else: ?>

                            <th class="text-center "><a
                                        href="<?php echo e(route('management.accounts.item',[ $item['id'],$account->id] )); ?>"><?php echo e($item['locale_name']); ?></a></th>
                        <?php endif; ?>
                        <th class="text-center "><?php echo e(money_format("%i", $item['total_debit'])); ?></th>
                        <th class="text-center "><?php echo e(money_format("%i", $item['total_credit'])); ?></th>
                        <th class="text-center "><?php echo e(money_format("%i", $item['balance_debit'])); ?></th>
                        <th class="text-center "><?php echo e(money_format("%i", $item['balance_credit'])); ?></th>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/learning/Desktop/code/zilrsoft/resources/views/accounts/items.blade.php ENDPATH**/ ?>