<?php

namespace App\Models;

    use App\Models\Traits\PostgresTimestamp;
    use Spatie\Permission\Models\Role as BaseRole;

    class Role extends BaseRole
    {
        //		protected $dateFormat = 'Y-m-d H:i:sO';

        use PostgresTimestamp;
    }
