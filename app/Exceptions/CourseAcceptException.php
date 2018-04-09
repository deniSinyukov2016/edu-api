<?php

namespace App\Exceptions;

use Exception;

class CourseAcceptException extends Exception
{
    protected $message = 'Can not accept course';
    protected $code = 400;
}
