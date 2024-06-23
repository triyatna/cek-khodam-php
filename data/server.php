<?php
include_once 'basisdata.php';
// $tes = fopen("listKhodam.txt", "r") or die("Unable to open file!");
// text
// $lines = file("listKhodam.txt");
// $random_line = $lines[array_rand($lines)];
// echo $random_line;



function checkWeton($tanggalLahir)
{

    $tanggalLahir = date('d-m-Y', strtotime($tanggalLahir));

    $jhari = 0;
    $array = explode("-", $tanggalLahir);
    $tgl = $array[0];
    $bln = $array[1];
    $thn = $array[2];

    $bulan = "Januari";
    switch ($bln) {
        case 2: {
                $bulan = "Pebruari";
                $jhari = 31;
                break;
            }
        case 3: {
                $bulan = "Maret";
                $jhari = 59;
                break;
            }
        case 4: {
                $bulan = "April";
                $jhari = 90;
                break;
            }
        case 5: {
                $bulan = "Mei";
                $jhari = 120;
                break;
            }
        case 6: {
                $bulan = "Juni";
                $jhari = 151;
                break;
            }
        case 7: {
                $bulan = "Juli";
                $jhari = 181;
                break;
            }
        case 8: {
                $bulan = "Agustus";
                $jhari = 212;
                break;
            }
        case 9: {
                $bulan = "September";
                $jhari = 243;
                break;
            }
        case 10: {
                $bulan = "Oktober";
                $jhari = 273;
                break;
            }
        case 11: {
                $bulan = "Nopember";
                $jhari = 304;
                break;
            }
        case 12: {
                $bulan = "Desember";
                $jhari = 334;
            }
    }
    $jml_kabisat = 1 + ($thn - ($thn % 4)) / 4;
    if ($thn > 100) $jml_kabisat -= ($thn - ($thn % 100)) / 100;
    if ($thn > 399) $jml_kabisat += ($thn - ($thn % 400)) / 400;
    if (($thn % 4) < 1 && $bln < 3) $jml_kabisat--;

    $jmlhari = $thn * 365 + $jhari + $tgl + $jml_kabisat;

    $urutan_hari = $jmlhari % 7;

    switch ($urutan_hari) {
        case 0:
            $hari = "Jumat";
            break;
        case 1:
            $hari = "Sabtu";
            break;
        case 2:
            $hari = "Minggu";
            break;
        case 3:
            $hari = "Senin";
            break;
        case 4:
            $hari = "Selasa";
            break;
        case 5:
            $hari = "Rabu";
            break;
        case 6:
            $hari = "Kamis";
    }

    $pasaran_jawa = $jmlhari % 5;
    switch ($pasaran_jawa) {
        case 0:
            $hari_jawa = "Kliwon";
            break;
        case 1:
            $hari_jawa = "Legi";
            break;
        case 2:
            $hari_jawa = "Pahing";
            break;
        case 3:
            $hari_jawa = "Pon";
            break;
        case 4:
            $hari_jawa = "Wage";
    }

    $hasil = $hari . " " . $hari_jawa . ", " . $tgl . " " . $bulan . " " . $thn;

    //blokir jika terjadi error saat eksekusi
    if ($array[2] > 5879610) $hasil = false; //terjadi error saat dijalankan di laptop pentium IV 1,7MHz dengan RAM 256MB
    if ($tgl > 28) {
        if ((($thn % 4) > 0 && $bln == 2) || $tgl > 30) {
            if ($bln != 1 || $bln != 3 || $bln != 5 || $bln != 7 || $bln != 8 || $bln != 10 || $bln != 12) {
                $hasil = false;
            }
        }
    }

    // neptu weton, watak, dan sifat
    $harilahir_ = [
        "Minggu" => 5,
        "Senin" => 4,
        "Selasa" => 3,
        "Rabu" => 7,
        "Kamis" => 8,
        "Jumat" => 6,
        "Sabtu" => 9,
    ];

    $pasaran_ = [
        "Kliwon" => 8,
        "Legi" => 5,
        "Pahing" => 9,
        "Pon" => 7,
        "Wage" => 4,
    ];
    // menghitung neptu weton dari tanggal lahir + pasaran
    $neptuweton = $harilahir_[$hari] + $pasaran_[$hari_jawa];
    // // watak berdasarkan jumlah neptu weton, 7-18
    // $watak = [
    //     "7" => "Pemarah, keras kepala, dan mudah tersinggung",
    //     "8" => "Pemalu, pendiam, dan pemikir",
    //     "9" => "Pemimpin, berani, dan tegas",
    //     "10" => "Pemurah, baik hati, dan penyayang",
    //     "11" => "Pemarah, keras kepala, dan mudah tersinggung",
    //     "12" => "Pemalu, pendiam, dan pemikir",
    //     "13" => "Pemimpin, berani, dan tegas",
    //     "14" => "Pemurah, baik hati, dan penyayang",
    //     "15" => "Pemarah, keras kepala, dan mudah tersinggung",
    //     "16" => "Pemalu, pendiam, dan pemikir",
    //     "17" => "Pemimpin, berani, dan tegas",
    //     "18" => "Pemurah, baik hati, dan penyayang",
    // ];

    return [
        "pasaran" => $hari . " " . $hari_jawa,
        "neptu" => $neptuweton,
        "result" => $hasil,
    ];
}


function checkKhodam($nama, $tgl)
{
    global $listKhodam;
    // tanggal lahir tidak boleh lebih dari hari ini
    if ($tgl > date('Y-m-d')) {
        return ['error' => 'Tanggal lahir tidak boleh lebih dari hari ini'];
    }
    $neptu = checkWeton($tgl)['neptu'];

    $nomor = abs(crc32($tgl . $nama . $neptu)) % 150;
    $id = $nomor;
    $khodam = $id >= count($listKhodam) ? $listKhodam[0] : $listKhodam[$id];
    $listKhodam[$id] = $khodam;
    $listKhodam[$id]['id'] = $id;
    return $listKhodam[$id];
}

function getKhodam($id)
{
    global $listKhodam;
    return $listKhodam[$id];
}

// Request Method Function with secure input
function post($key)
{
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : '';
}
function get($key)
{
    return isset($_GET[$key]) ? htmlspecialchars($_GET[$key]) : '';
}

function redirect($url)
{
    header("Location: $url");
    exit();
}

// function set, get, delete cookie 
function setCookieData($key, $value)
{
    setcookie($key, $value, time() + (86400 * 30), "/");
}
function getCookieData($key)
{
    return isset($_COOKIE[$key]) ? $_COOKIE[$key] : '';
}
function deleteCookieData($key)
{
    setcookie($key, "", time() - 3600, "/");
}
