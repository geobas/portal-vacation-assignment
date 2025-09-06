<?php

declare(strict_types=1);

use Exception;
use RuntimeException;

/**
 * Render a PHP view template and return it as a string.
 *
 * @param string $viewPath Relative path to the view file.
 * @param array<string, mixed> $data Associative array of variables to extract into the view.
 * @return string Rendered view output.
 */
function view(string $viewPath, array $data = []): string
{
    $fullPath = __DIR__ . '/../Views/' . $viewPath;

    if (!file_exists($fullPath)) {
        throw new Exception("View file not found: {$fullPath}");
    }

    extract($data, EXTR_SKIP); // Extract variables to local scope
    ob_start();
    include $fullPath;

    $output = ob_get_clean();
    if ($output === false) {
        throw new RuntimeException("Failed to fetch output buffer for view: {$fullPath}");
    }

    return $output;
}
