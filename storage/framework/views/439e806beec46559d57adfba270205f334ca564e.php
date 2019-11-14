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
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>



                    <tr>
                        <th class="text-center "><?php echo e($client->created_at); ?></th>
                        <th class="text-center "><?php echo e($client->id); ?></th>

                        <th class="text-center "><a href="<?php echo e(route('management.charts.client',[
                             $client->id,$chart->id])); ?>"><?php echo e($client->name); ?></a></th>
                        <th class="text-center ">-</th>
                        <th class="text-center "><?php echo e(money_format("%i",$client->histories['total_debit'])); ?></th>
                        <th class="text-center "><?php echo e(money_format("%i",$client->histories['total_credit'])); ?></th>
                        <th class="text-center "><?php echo e(money_format("%i",$client->histories['total_debit'] -
                         $client->histories['total_credit'])); ?></th>

                    </tr>




                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/accounting/clients_histories.blade.php ENDPATH**/ ?>