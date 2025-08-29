<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class HttpException extends Exception
{
    public function __construct(
        string $message,
        public int $statusCode = 400
    ) {
        parent::__construct($message, $statusCode);
    }
}
