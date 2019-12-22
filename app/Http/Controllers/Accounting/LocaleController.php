<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController
{

    public function __invoke()
    {
        return app()->getLocale();
    }

}
