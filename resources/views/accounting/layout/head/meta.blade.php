<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<meta name="primary-color" content="#0984e3">
<meta name="second-color" content="#3498db">
<meta name="app-locate" content="{{ app()->getLocale()  }}">
<meta name="app-name" content="{{ config('app.name')  }}">
<meta name="app-base-url" content="{{ route('accounting.dashboard.index')  }}">


<meta name="lang-sidebar" content="{{ json_encode(trans('sidebar')) }}">
<meta name="lang-pagination" content="{{ json_encode(trans('pagination')) }}">
<meta name="lang-messages" content="{{ json_encode(trans('messages')) }}">
<meta name="lang-datetime" content="{{ json_encode(trans('datetime')) }}">
<meta name="lang-table" content="{{ json_encode(trans('table')) }}">
<meta name="lang-reusable-translator" content="{{ json_encode(trans('reusable')) }}">
<meta name="lang-validation" content="{{ json_encode(trans('validation')) }}">
<meta name="lang-items-page" content="{{ json_encode(trans('pages/items')) }}">
<meta name="lang-invoices-page" content="{{ json_encode(trans('pages/invoice')) }}">
<meta name="lang-vouchers-page" content="{{ json_encode(trans('pages/vouchers')) }}">
<meta name="lang-users-page" content="{{ json_encode(trans('pages/users')) }}">
<meta name="lang-filters-page" content="{{ json_encode(trans('pages/filters')) }}">
<meta name="lang-branches-page" content="{{ json_encode(trans('pages/branches')) }}">
<meta name="lang-categories-page" content="{{ json_encode(trans('pages/categories')) }}">
<meta name="datatableBaseUrl" content="/accounting/datatable/">
<meta name="BaseApiUrl" content="/accounting/">
