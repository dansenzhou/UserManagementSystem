<?php

namespace AppBundle\Exception;

interface IException
{
    /**
     * Return log message for log system
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @return string
     */
    public function getLogMessage(): string;

    /**
     * Return error message
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @return string
     */
    public function getErrorMessage(): string;

    /**
     * Return if the error message should be exposed to client
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @return bool
     */
    public function isClientError(): bool;
}