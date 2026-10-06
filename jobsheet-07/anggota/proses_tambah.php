<?php
session_start();

// Cek apakah data dikirim melalui method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telepon  = trim($_POST['telepon'] ?? '');
    $alamat   = trim($_POST['alamat'] ?? '');

    $errors = [];

    // 1. Validasi Nama Lengkap
    if ($nama === '') {
        $errors[] = "Nama lengkap wajib diisi.";
    } elseif (!preg_match("/^[a-zA-Z\s'-]+$/", $nama)) {
        $errors[] = "Nama hanya boleh berisi huruf, spasi, tanda petik, atau tanda hubung.";
    }

    // 2. Validasi Email
    if ($email === '') {
        $errors[] = "Email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format alamat email tidak valid.";
    }

    // 3. Validasi Nomor Telepon (Opsional tapi jika diisi harus angka)
    if ($telepon !== '' && (!is_numeric($telepon) || strlen($telepon) < 10)) {
        $errors[] = "Nomor telepon harus berupa angka dan minimal 10 digit.";
    }

    // Jika ada error, simpan pesan error ke flash session dan kembalikan ke form tambah
    if (!empty($errors)) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => implode(' ', $errors)
        ];
        header('Location: tambah.php');
        exit;
    }

    // Jika lolos validasi, simpan ke $_SESSION['anggota']
    if (!isset($_SESSION['anggota'])) {
        $_SESSION['anggota'] = [];
    }

    $_SESSION['anggota'][] = [
        'nama'    => $nama,
        'email'   => $email,
        'telepon' => $telepon,
        'alamat'  => $alamat
    ];

    // Set flash message sukses dan redirect ke halaman list anggota
    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Anggota baru berhasil ditambahkan.'
    ];
    header('Location: list.php');
    exit;
} else {
    // Jika diakses langsung tanpa POST, kembalikan ke form
    header('Location: tambah.php');
    exit;
}