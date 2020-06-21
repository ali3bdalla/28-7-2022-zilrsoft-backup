<?php


namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class BaseAuthModel extends  Authenticatable
{
    use Notifiable,HasRoles;
    //HasPermissions
}