<?php

namespace App\Repository\Eloquent;

use App\Models\Manager;
use App\Models\User;
use App\Repository\BaseRepositoryContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseRepository implements BaseRepositoryContract
{
    public function authUser(): ?User
    {
        return Auth::guard('client')->user();
    }
    public function authManager(): ?Manager
    {
        return Auth::user();
    }
}
