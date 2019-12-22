<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<meta name="primary-color" content="#0984e3">
<meta name="second-color" content="#3498db">
<meta name="app-locate" content="<?php echo e(app()->getLocale()); ?>">
<meta name="app-name" content="<?php echo e(config('app.name')); ?>">
<meta name="app-base-url" content="<?php echo e(route('accounting.dashboard.index')); ?>">


<meta name="lang-sidebar" content="<?php echo e(json_encode(trans('sidebar'))); ?>">
<meta name="lang-pagination" content="<?php echo e(json_encode(trans('pagination'))); ?>">
<meta name="lang-messages" content="<?php echo e(json_encode(trans('messages'))); ?>">
<meta name="lang-datetime" content="<?php echo e(json_encode(trans('datetime'))); ?>">
<meta name="lang-table" content="<?php echo e(json_encode(trans('table'))); ?>">
<meta name="lang-reusable-translator" content="<?php echo e(json_encode(trans('reusable'))); ?>">
<meta name="lang-validation" content="<?php echo e(json_encode(trans('validation'))); ?>">
<meta name="lang-items-page" content="<?php echo e(json_encode(trans('pages/items'))); ?>">
<meta name="lang-users-page" content="<?php echo e(json_encode(trans('pages/users'))); ?>">
<meta name="lang-filters-page" content="<?php echo e(json_encode(trans('pages/filters'))); ?>">
<meta name="lang-categories-page" content="<?php echo e(json_encode(trans('pages/categories'))); ?>">
<meta name="datatableBaseUrl" content="/accounting/datatable/">
<meta name="BaseApiUrl" content="/accounting/">
<?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/layout/head/meta.blade.php ENDPATH**/ ?>