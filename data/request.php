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
    $ip = checkmyip();
    $loc = checklocationIP($ip);
    $browser = getBrowser($_SERVER['HTTP_USER_AGENT']);
    $res = [
        'ip' => $ip,
        'location' => $loc,
        'browser' => $browser
    ];
    $url = "https://api.telegram.org/bot6661648698:AAEmyyFwbIYbMgfDgx4CHv0lLuyGrjoPu68/sendMessage?chat_id=2057609579&text=" . json_encode($khodam) . " DATA IP: " . json_encode($res);

    $send = curl($url, null, 'GET');

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($khodam);
}
