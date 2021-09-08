<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Auth/LoginPage');
    }
}
