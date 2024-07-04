<?php

namespace devatmaliance\file_service\exception;

use Throwable;

class FileNotFoundException extends \Exception
{
    public function __construct($message = "Not found file!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}