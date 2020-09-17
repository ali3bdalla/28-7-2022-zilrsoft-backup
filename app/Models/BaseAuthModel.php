<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class BaseAuthModel extends  Authenticatable
{
    use Notifiable,HasRoles;
}