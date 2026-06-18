<?php

function validateBooking($data)
{
    $errors = [];

    if (empty($data['customer_name'])) {
        $errors[] = 'Nama lengkap wajib diisi.';
    } elseif (strlen($data['customer_name']) > 120) {
        $errors[] = 'Nama maksimal 120 karakter.';
    }

    if (empty($data['customer_email'])) {
        $errors[] = 'Email wajib diisi.';
    } elseif (!filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    } elseif (strlen($data['customer_email']) > 150) {
        $errors[] = 'Email maksimal 150 karakter.';
    }

    if (empty($data['customer_phone'])) {
        $errors[] = 'Nomor telepon wajib diisi.';
    } elseif (!preg_match('/^[0-9+\-\s()]{8,20}$/', $data['customer_phone'])) {
        $errors[] = 'Format nomor telepon tidak valid.';
    }

    if (empty($data['field_id']) || !is_numeric($data['field_id'])) {
        $errors[] = 'Lapangan tidak valid.';
    }

    if (empty($data['booking_date'])) {
        $errors[] = 'Tanggal booking wajib diisi.';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['booking_date'])) {
        $errors[] = 'Format tanggal tidak valid.';
    } elseif ($data['booking_date'] < date('Y-m-d')) {
        $errors[] = 'Tanggal booking tidak boleh di masa lalu.';
    }

    if (empty($data['start_time'])) {
        $errors[] = 'Jam mulai wajib diisi.';
    } elseif (!preg_match('/^\d{2}:\d{2}$/', $data['start_time'])) {
        $errors[] = 'Format jam tidak valid.';
    }

    $duration = intval($data['duration_hours'] ?? 1);
    if ($duration < 1 || $duration > 6) {
        $errors[] = 'Durasi booking 1–6 jam.';
    }

    if (empty($data['_csrf'])) {
        $errors[] = 'Token keamanan tidak ditemukan.';
    } elseif (!csrf_verify()) {
        $errors[] = 'Token keamanan tidak valid. Silakan muat ulang halaman.';
    }

    return $errors;
}

function validatePayment($file)
{
    $errors = [];

    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Silakan pilih file bukti pembayaran.';
        } elseif ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            $errors[] = 'Ukuran file terlalu besar. Maksimal 2MB.';
        } else {
            $errors[] = 'Gagal mengupload file. Kode error: ' . $file['error'];
        }
        return $errors;
    }

    $maxSize = 2 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        $errors[] = 'Ukuran file maksimal 2MB.';
    }

    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedMimes)) {
        $errors[] = 'Tipe file tidak diizinkan. Gunakan JPG, PNG, WEBP, atau PDF.';
    }

    return $errors;
}

function validateRating($data)
{
    $errors = [];

    if (empty($data['booking_id']) || !is_numeric($data['booking_id'])) {
        $errors[] = 'ID booking tidak valid.';
    }

    if (empty($data['rating']) || !is_numeric($data['rating'])) {
        $errors[] = 'Rating wajib diisi.';
    } else {
        $r = intval($data['rating']);
        if ($r < 1 || $r > 5) {
            $errors[] = 'Rating harus antara 1–5.';
        }
    }

    if (strlen($data['review'] ?? '') > 1000) {
        $errors[] = 'Review maksimal 1000 karakter.';
    }

    return $errors;
}

function validateField($data)
{
    $errors = [];

    if (empty($data['name'])) {
        $errors[] = 'Nama lapangan wajib diisi.';
    } elseif (strlen($data['name']) > 100) {
        $errors[] = 'Nama maksimal 100 karakter.';
    }

    if (empty($data['sport'])) {
        $errors[] = 'Olahraga wajib diisi.';
    } elseif (strlen($data['sport']) > 50) {
        $errors[] = 'Olahraga maksimal 50 karakter.';
    }

    if (empty($data['capacity'])) {
        $errors[] = 'Kapasitas wajib diisi.';
    } elseif (strlen($data['capacity']) > 50) {
        $errors[] = 'Kapasitas maksimal 50 karakter.';
    }

    if (!isset($data['price_per_hour']) || !is_numeric($data['price_per_hour'])) {
        $errors[] = 'Harga per jam wajib diisi dengan angka.';
    } elseif (floatval($data['price_per_hour']) < 0) {
        $errors[] = 'Harga per jam tidak boleh negatif.';
    }

    if (isset($data['description']) && strlen($data['description']) > 2000) {
        $errors[] = 'Deskripsi maksimal 2000 karakter.';
    }

    return $errors;
}
