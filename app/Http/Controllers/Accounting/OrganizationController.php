<?php

namespace App\Http\Controllers;

use App\Gateway;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

    public function get_ways_with_accounts_that_organization_has_account_on_them(Gateway $payWay){
        return $payWay->with(['children' => function($query) {
            return $query->with(['accounts'=> function($query2) {
                return $query2->whereIn('id',auth()->user()->organization->accounts()->pluck('id'));
            }

            ])->whereHas('accounts',function($query2)  {
                return $query2->whereIn('id',auth()->user()->organization->accounts()->pluck('id'));
            });

        },'accounts' => function($query2) {
            return $query2->whereIn('id',auth()->user()->organization->accounts()->pluck('id'));
        }])->first();
    }
    //
}
