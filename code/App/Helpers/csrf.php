<?php

declare(strict_types=1);

/**
 * Generate or get the CSRF token for the current session.
 *
 * @return string
 */
function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Render a hidden input field with the CSRF token for forms.
 *
 * @return string
 */
function csrf_field(): string
{
    $token = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');

    return "<input type=\"hidden\" name=\"csrf_token\" value=\"{$token}\">";
}

/**
 * Validate the CSRF token submitted in a POST request.
 *
 * @param string|null $token
 * @return void
 */
function validate_csrf(?string $token): void
{
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        $_SESSION['error'] = 'Invalid CSRF token';
        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
    // Optionally make token single-use
    unset($_SESSION['csrf_token']);
}
