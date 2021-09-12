<?php

namespace App\Exceptions;

use Nette\Schema\ValidationException;

class ClientInvalidAuthenticationCredentialsException extends ValidationException
{
    //
    public function __construct()
    {
        parent::__construct("Invalid Credentials", ['phone_number' => 'Invalid Phone Number']);
    }
}
