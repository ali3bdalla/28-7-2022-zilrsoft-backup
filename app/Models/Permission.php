<?php

namespace App\Models;

    use App\Models\Traits\PostgresTimestamp;
    use Spatie\Permission\Models\Permission as BasePermission;

    class Permission extends BasePermission
    {
        //		protected $dateFormat = 'Y-m-d H:i:sO';

        use PostgresTimestamp;
    }
