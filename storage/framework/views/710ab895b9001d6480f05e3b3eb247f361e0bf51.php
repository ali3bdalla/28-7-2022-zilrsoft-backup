<?php $__env->startSection('title',$chart->name); ?>











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
                $total_amount = 0;
                ?>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php
                        $total_amount = $total_amount + $history->receipt_total - $history->payment_total;
                        ?>


                        <tr>
                            <th class="text-center "><?php echo e($history->created_at); ?></th>
                            <th class="text-center "><?php echo e($history->id); ?></th>
                            <th class="text-center ">-</th>
                            <th class="text-center "><a href="<?php echo e(route('management.charts.item',
                             $history->id)); ?>"><?php echo e($history->name); ?></a> </th>
                            <th class="text-center "><?php echo e($history->receipt_total); ?></th>
                            <th class="text-center "><?php echo e($history->payment_total); ?></th>
                            <th class="text-center "><?php echo e($total_amount); ?></th>

                        </tr>




                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\man-v\OneDrive\Desktop\server\htdocs\resources\views/accounting/stock_histories.blade.php ENDPATH**/ ?>