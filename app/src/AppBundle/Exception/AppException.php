<?php

namespace AppBundle\Exception;


class AppException extends \Exception implements IException
{
    /**
     * @var string
     */
    private $logMessage;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @var bool
     */
    private $isClientError;

    public function __construct($logMessage, $errorMessage, $isClientError = false, $code = 0)
    {
        $this->logMessage = $logMessage;
        $this->errorMessage = $errorMessage;
        $this->isClientError = $isClientError;

        parent::__construct($errorMessage, $code, null);
    }

    /**
     * @return string
     */
    public function getLogMessage(): string
    {
        return $this->logMessage;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @return bool
     */
    public function isClientError(): bool
    {
        return $this->isClientError;
    }
}