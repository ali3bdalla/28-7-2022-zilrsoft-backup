<?php

namespace App\Http\Controllers\Store\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGateways;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PaymentAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param User $user
     * @return Collection
     */
    public function index(Request $request, User $user): Collection
    {
        $userId = $user->id;

        if ($request->user('client')) {
            $userId = $request->user('client')->id;
        }

        return UserGateways::where('user_id', $userId)->with('bank')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Model
     */
    public function store(Request $request, User $user): Model
    {
        $userId = $user->id;

        if ($request->user('client')) {
            $userId = $request->user('client')->id;
        }


        $request->validate(
            [
                'bank_id' => 'required|integer|exists:banks,id',
                'detail' => 'required|string',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
            ]
        );
        $data = $request->only('bank_id', 'detail', 'first_name', 'last_name');
        $data['organization_id'] = 1;
        $data['creator_id'] = 1;
        $data['user_id'] = $userId;

        $account = $user->gateways()->create($data);

        return $account->load('bank');

    }

}
