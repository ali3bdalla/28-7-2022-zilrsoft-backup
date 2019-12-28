<script defer>
    window.config = `<?php echo json_encode($organization_config, 15, 512) ?>`

</script>

<script src="https://cdn.jsdelivr.net/npm/rsvp@4/dist/rsvp.min.js" defer></script>

<script defer>
    window.reusable_translator = `<?php echo json_encode(trans('reusable'), 15, 512) ?>`;
    window.messages = `<?php echo json_encode(trans('messages'), 15, 512) ?>`
</script>


<script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/layout/head/defer_js.blade.php ENDPATH**/ ?>