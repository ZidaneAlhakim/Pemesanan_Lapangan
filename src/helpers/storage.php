<?php

define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024);
define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);
define('UPLOAD_DIR', __DIR__ . '/../../public/assets/uploads');

function storage_put($file, $subdir = 'payments')
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if ($file['size'] > UPLOAD_MAX_SIZE) {
        return null;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, UPLOAD_ALLOWED_TYPES)) {
        return null;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safeExt = preg_replace('/[^a-zA-Z0-9]/', '', $ext);
    $prefix = ($subdir === 'fields') ? 'field_' : 'payment_';
    $filename = $prefix . uniqid() . '_' . time() . '.' . $safeExt;

    $yearDir = date('Y');
    $monthDir = date('m');
    $relativePath = $subdir . '/' . $yearDir . '/' . $monthDir;
    $fullDir = UPLOAD_DIR . '/' . $relativePath;

    if (!is_dir($fullDir)) {
        mkdir($fullDir, 0755, true);
    }

    $targetPath = $fullDir . '/' . $filename;
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $relativePath . '/' . $filename;
    }

    return null;
}

function storage_delete($path)
{
    if (empty($path)) {
        return false;
    }
    $fullPath = UPLOAD_DIR . '/' . ltrim($path, '/');
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}

function storage_url($path)
{
    if (empty($path)) {
        return '';
    }
    return base_url('assets/uploads/' . ltrim($path, '/'));
}

function storage_exists($path)
{
    if (empty($path)) {
        return false;
    }
    return file_exists(UPLOAD_DIR . '/' . ltrim($path, '/'));
}
