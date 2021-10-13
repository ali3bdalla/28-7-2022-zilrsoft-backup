<?php

namespace App\Models;

    use App\Models\Traits\PostgresTimestamp;
    use Spatie\Permission\Models\Role as BaseRole;

    class Role extends BaseRole
    {
        use PostgresTimestamp;
    }
