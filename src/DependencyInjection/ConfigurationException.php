<?php

namespace Smoq\SimsyCMS\DependencyInjection;

use Exception;

class ConfigurationException extends Exception
{
    public function __construct(string $message = 'Configuration error', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}