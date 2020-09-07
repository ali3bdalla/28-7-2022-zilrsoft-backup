<?php $__currentLoopData = $account['mainAccountChildren']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tbody style="margin-right: 5px !important;" class="table-body-child">
    <?php if($account2->children()->count() > 0): ?>
        <tr>
            <td colspan="" class="text-bold" style="padding-right:<?php echo e($padding); ?>px;text-align: right !important;"><?php echo e($account2->locale_name); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

        </tr>
    <?php endif; ?>
    </tbody>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>






<?php /**PATH /usr/local/var/www/workspace/zilrsoft/Modules/Accounting/Resources/views/trial_balance/table_cell.blade.php ENDPATH**/ ?>