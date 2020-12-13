Dear ~<?php echo e($client->name); ?>~,

Thank you for your order

Order# <?php echo e($order->id); ?>


Amount: *<?php echo e(displayMoney($order->net)); ?>*

Banks Accounts:

    Rajhi:   *38238258237951*

    Alahli:  *54564646546546*

    Albilad: *23749823749873*

    Deadline for Payment <?php echo e($deadline); ?>



Please Fill This Form After Transfer
<?php echo e($order->generatePayOrderUrl()); ?>



To Cancel Your Order Please Click Flowing Link and use this code <?php echo e($order->cancel_order_code); ?>:
<?php echo e($order->generateCancelOrderUrl()); ?>



<?php echo e($invoice->organization->title); ?><?php /**PATH /private/var/www/workspace/zilrsoftproject/resources/views/whatsapp/order_details.blade.php ENDPATH**/ ?>