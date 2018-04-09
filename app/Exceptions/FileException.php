<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.03.18
 * Time: 15:25
 */

namespace App\Exceptions;

use Exception;

class FileException extends Exception
{
    protected $message = 'Can not add file.';
    protected $code = 400;
}

