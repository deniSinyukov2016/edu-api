<?php

namespace App\Exceptions;

use Exception;

class ImageException extends Exception
{
    protected $message = 'Can not add image. Image exist or other';
    protected $code = 400;
}
