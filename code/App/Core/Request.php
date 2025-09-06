<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    /**
     * Get request body data.
     *
     * @return array<string, mixed>
     */
    public function getBody(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        }

        return $_GET;
    }
}
