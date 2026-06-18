<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function base_url($path = '')
{
    $base = rtrim((!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/\\');
    return $base . '/' . ltrim($path, '/');
}

function asset($path)
{
    return base_url('assets/' . ltrim($path, '/'));
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function old($key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}

function flash($key = 'success', $message = '')
{
    if ($message) {
        $_SESSION['flash_' . $key] = $message;
    } else {
        $msg = $_SESSION['flash_' . $key] ?? '';
        unset($_SESSION['flash_' . $key]);
        return $msg;
    }
}

function flash_has($key = 'success')
{
    return isset($_SESSION['flash_' . $key]);
}

function csrf_token()
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field()
{
    return '<input type="hidden" name="_csrf" value="' . csrf_token() . '">';
}

function csrf_verify()
{
    $token = $_POST['_csrf'] ?? '';
    $valid = !empty($token) && hash_equals($_SESSION['_csrf'] ?? '', $token);
    if (!$valid) {
        flash('error', 'Session expired. Silakan coba lagi.');
    }
    return $valid;
}

function csrf_regenerate()
{
    $_SESSION['_csrf'] = bin2hex(random_bytes(32));
}

function method_field($method)
{
    return '<input type="hidden" name="_method" value="' . $method . '">';
}
