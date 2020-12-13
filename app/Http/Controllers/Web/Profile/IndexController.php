<?php

namespace App\Http\Controllers\Web\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Profile\UpdateInformationRequest;
use App\Http\Requests\Store\Profile\UpdatePasswordRequest;
use App\Http\Requests\Store\Profile\UpdatePhoneNumberRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    //

    public function index(Request $request)
    {
        return Inertia::render('Web/Profile/Index', [
            'user' => $request->user()
        ]);
    }

    public function updateInformation(UpdateInformationRequest $request)
    {
        return $request->update();
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        return $request->update();
    }

    public function updatePhoneNumber(UpdatePhoneNumberRequest $request)
    {
        return $request->change();
    }
}
