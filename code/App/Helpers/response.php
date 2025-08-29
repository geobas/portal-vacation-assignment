<?php

declare(strict_types=1);

/**
 * Redirect to a given URL and exit.
 *
 * @param string $url
 */
function redirect(string $url): void
{
    header("Location: {$url}");
    exit;
}
