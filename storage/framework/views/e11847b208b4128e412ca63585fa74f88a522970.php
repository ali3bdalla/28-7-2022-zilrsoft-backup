<div class="wrapper" id="app">
    <?php if ($__env->exists('accounting.layout.head.header')) echo $__env->make('accounting.layout.head.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <?php echo $__env->yieldContent('title'); ?>
                <small><?php echo $__env->yieldContent('buttons'); ?></small>
            </h1>
        </section>
        <div class="content">
            <?php echo $__env->yieldContent("before_content"); ?>
            <?php echo $__env->yieldContent('content'); ?>
            <?php echo $__env->yieldContent("after_content"); ?>
        </div>
    </div>
    <aside class="main-sidebar" style="z-index:108;padding-top: 0px !important;">
        <?php if ($__env->exists('accounting.layout.head.sidebar')) echo $__env->make('accounting.layout.head.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </aside>
    <footer class="main-footer">
        <?php if ($__env->exists('layouts.footer')) echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </footer>
</div><?php /**PATH /var/www/vhosts/zilrsoft.com/resources/views/accounting/layout/layout.blade.php ENDPATH**/ ?>