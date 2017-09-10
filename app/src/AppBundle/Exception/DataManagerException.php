<?php

namespace AppBundle\Exception;

use AppBundle\Constant\ExceptionCode;

class DataManagerException extends AppException
{
    public function __construct($logMessage, $errorMessage = "", $isClientError = false, $code = ExceptionCode::UNKNOWN)
    {
        parent::__construct($logMessage, $errorMessage, $isClientError, $code);
    }
}