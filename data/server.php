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

function curlGET($url, $useSsl = true)
{
    $ch = curl_init();

    // Set the URL
    curl_setopt($ch, CURLOPT_URL, $url);

    // Set SSL options
    if ($useSsl) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    } else {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }

    // Return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    // Close the cURL handle
    curl_close($ch);

    return $response;
}

function curlPOST($url, $data, $useSsl = true)
{
    $ch = curl_init();

    // Set the URL
    curl_setopt($ch, CURLOPT_URL, $url);

    // Set the HTTP method to POST
    curl_setopt($ch, CURLOPT_POST, true);

    // Set the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Set SSL options
    if ($useSsl) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    } else {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }

    // Return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    // Close the cURL handle
    curl_close($ch);

    return $response;
}
function checkmyip()
{
    if ($_SERVER['REMOTE_ADDR'] == '::1') {
        $ip = 'localhost';
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function checklocationIP($ip)
{
    $url = "https://api.ipgeolocation.io/ipgeo?apiKey=19926fae8159428ba3967354e5b86a2b&ip=" . $ip;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($response);
    return $data;
}

function getBrowser($user_agent)
{
    $u_agent = $user_agent;
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    } elseif (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $u_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($u_agent, 0, 4))) {
        $platform = 'Android Browser';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/OPR/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
        $bname = 'Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    } elseif (preg_match('/Edge/i', $u_agent)) {
        $bname = 'Edge';
        $ub = "Edge";
    } elseif (preg_match('/Trident/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $u_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($u_agent, 0, 4))) {
        $bname = 'Android Browser';
        $ub = "Android Browser";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}
