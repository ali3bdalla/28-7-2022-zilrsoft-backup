<script defer>
    window.config = `<?php echo json_encode($organization_config, 15, 512) ?>`

</script>

<script src="<?php echo e(asset('accounting/js/rsvp.min.js')); ?>" defer></script>

<script defer>
    window.reusable_translator = `<?php echo json_encode(trans('reusable'), 15, 512) ?>`;
    window.messages = `<?php echo json_encode(trans('messages'), 15, 512) ?>`
</script>


<script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
<script defer src="<?php echo e(asset('accounting/js/font_awesome.js')); ?>"></script><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/resources/views/accounting/layout/head/defer_js.blade.php ENDPATH**/ ?>