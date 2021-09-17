<?php

namespace App\Repository;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

interface BaseRepositoryContract
{
    public function authUser(): ?User;
    public function authManager(): ?Manager;
}
