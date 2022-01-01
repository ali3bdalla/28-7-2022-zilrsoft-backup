<?php

namespace App\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CurrentYearScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
//        $authUser = Auth::user();
//        if ($authUser && $authUser->organization_id === 1) {
//            if ($authUser->id !== 2)
//                $builder->whereYear('created_at', Carbon::now());
//            else
//                $builder->whereYear('created_at', '<', Carbon::now());
//        }
    }
}
