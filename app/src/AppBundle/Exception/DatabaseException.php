<?php

namespace AppBundle\Exception;

use AppBundle\Constant\ExceptionCode;

class DatabaseException extends AppException
{
    public function __construct($logMessage)
    {
        parent::__construct($logMessage, "", false, ExceptionCode::DATABASE);
    }
}