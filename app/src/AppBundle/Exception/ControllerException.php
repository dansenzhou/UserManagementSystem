<?php

namespace AppBundle\Exception;

use AppBundle\Constant\ExceptionCode;

class ControllerException extends AppException
{
    public function __construct($logMessage, $errorMessage = "", $isClientError = false, $code = ExceptionCode::UNKNOWN)
    {
        parent::__construct($logMessage, $errorMessage, $isClientError, $code);
    }
}