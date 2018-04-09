<?php

namespace App\Exceptions;

class NotConfirmedUser extends \Exception
{
    protected $message = 'User not confirmed';
    protected $code = 403;
}
