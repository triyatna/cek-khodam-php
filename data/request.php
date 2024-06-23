<?php

include_once 'server.php';

// hanya diizinkan method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method Not Allowed');
}

if (post('nama') && post('tanggal')) {
    $nama = post('nama');
    $tgl = post('tanggal');
    // tanggal lahir tidak boleh lebih dari hari ini
    if ($tgl > date('Y-m-d')) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Tanggal lahir tidak boleh lebih dari hari ini']);
        exit();
    }
    $khodam = checkKhodam($nama, $tgl);
    // tambahkan nama dan tanggal lahir ke khodam
    $khodam['nama'] = $nama;
    $khodam['tanggal'] = $tgl;
    $weton = checkWeton($tgl);
    $khodam['weton'] = $weton;
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($khodam);
}
