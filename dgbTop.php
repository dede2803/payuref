<?php
system('clear');
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
//session_start();

//WARNA
$RedTebal    = "\33[1;31m";
$GreenTebal  = "\33[1;32m";
$YellowTebal = "\33[1;33m";
$PurpleTebal = "\33[1;34m";
$PinkTebal   = "\33[1;35m";
$CyanTebal   = "\33[1;36m";
$WhiteTebal  = "\33[1;37m";
$NormalTebal = "\33[1m";

function wktClaim($tmr)
{
    $timr = time() + $tmr;
    while (true) :
        echo "\r                                            \r";
        $res = $timr - time();
        if ($res < 1) {
            break;
        }
        echo "\33[1;37mTunggu, sedang melakukan claim \33[1;31m" . date('s', $res) . "\33[1;37m detik.";
        sleep(1);
    endwhile;
}

function timer($tmr)
{
    $timr = time() + $tmr;
    while (true) :
        echo "\r                                            \r";
        $res = $timr - time();
        if ($res < 1) {
            break;
        }
        echo "\33[1;37mTunggu, proses selanjutnya selama \33[1;31m" . date($res) . "\33[1;37m detik.";
        sleep(1);
    endwhile;
}


//PARSING JSON
function fetch_value($str, $find_start, $find_end)
{
    $start = @strpos($str, $find_start);
    if ($start === false) {
        return "";
    }
    $length = strlen($find_start);
    $end = strpos(substr($str, $start + $length), $find_end);
    return trim(substr($str, $start + $length, $end));
}


//SAVE JSON
function save($data, $data_post)
{
    if (!file_get_contents($data)) {
        file_put_contents($data, "[]");
    }

    $json = json_decode(file_get_contents($data), 1);
    $arr = array_merge($json, $data_post);
    file_put_contents($data, json_encode($arr, JSON_PRETTY_PRINT));
}


//============================ CURL =============================================
function curl($url, $post = 0, $httpheader = 0, $proxy = 0)
{
    // url, postdata, http headers, proxy, uagent
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_COOKIE, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "g.txt");
    curl_setopt($ch, CURLOPT_COOKIEJAR, "g.txt");

    if ($post) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }

    if ($httpheader) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    }

    if ($proxy) {
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        // curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    }

    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch);
    if (!$httpcode) return "Curl Error : " . curl_error($ch);
    else {
        $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        curl_close($ch);
        return array($header, $body);
    }
}

function headIP()
{
    $h[] = "Accept: text/html,application/xhtml+xml,application/xml;";
    $h[] = "User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36";
    return $h;
}

function getIP()
{
    $url = "https://ipsaya.com/";
    return curl($url, '', headIP())[1];
}


$getIP = getIP();
$IP = explode('">', explode('<input type="hidden" name="lihatip" id="lihatip"  value="', $getIP)[1])[0];
if ($IP != "") {
    echo $WhiteTebal . "[7] SERVER DGB PAYU TOP";
    sleep(5);
    echo "\r                                                          \r";
    function head()
    {
        //$cookie = json_decode(file_get_contents('cookie.json'),1);
        $h[] = "Accept: text/html,application/xhtml+xml,application/xml;";
        $h[] = "User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36";
        $h[] = "content-type: application/x-www-form-urlencoded";
        //$h[] = "cookie: ".$cookie['cookie'];
        return $h;
    }

    //input email
    if (!file_exists("email.json")) {
        system('clear');
        while ("true") {
            $a = readline("\033[1;97mAlamat email : \033[1;92m");
            if (!$a == "") {
                break;
            }
            system('clear');
        }
        $data = ["email" => $a];
        save("email.json", $data);
    }

    function get()
    {
        $url = "https://dgbpayu.top";
        return curl($url, '', head())[1];
    }

    function login($email, $token, $header)
    {
        $url = "https://dgbpayu.top/auth/login";
        $data = http_build_query(array("wallet" => $email, "csrf_token_name" => $token));
        return curl($url, $data, $header)[1];
    }

    function test()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://dgbpayu.top/faucet/currency/dgb");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
        $server_output = curl_exec($ch);
        curl_close($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $httpcode;
    }

    function currency()
    {
        $url = "https://dgbpayu.top/faucet/currency/dgb";
        return curl($url, '', head())[1];
    }

    function claim($aft, $ctn, $tkn, $header)
    {
        $url = "https://dgbpayu.top/faucet/verify/dgb";
        $data = http_build_query(array('auto_faucet_token' => $aft, 'csrf_token_name' => $ctn, 'token' => $tkn));
        return curl($url, $data, $header)[1];
    }

    function afterClaim($header)
    {
        $url = "https://dgbpayu.top/faucet/currency/dgb";
        return curl($url, '', $header)[1];
    }

    $email  = json_decode(file_get_contents('email.json'), 1);

    $get    = get();
    $email  = $email['email'];
    $token  = explode('">', explode('<input type="hidden" name="csrf_token_name" id="token" value="', $get)[1])[0];
    $login  = login($email, $token, array_merge(head(), array("Referer: https://dgbpayu.top/?r=144")));
    sleep(5);


    while (true) {
        for ($i = 0; $i < 500; $i++) {
            $check = test();

            if ($check == "404") {
                echo $WhiteTebal . "Situs Website" . $RedTebal . " Tidak ada," . $WhiteTebal . " coba periksa kembali" . $YellowTebal . " SITUS LINK WEBSITE..!!! \n";
            }
            $currency = currency();
            $sttsBal = explode('</span></h6>', explode('<span class="badge badge-danger">', $currency)[1])[0];

            if ($sttsBal == "Empty") {
                echo $check2;
                echo $WhiteTebal . "[7] " . $YellowTebal . "DGB PAYU TOP " . $RedTebal . "Sedang Kosong. " . $WhiteTebal . "Tunggu Besok Lagi !!!\n";
                sleep(2);
                system("php dgbXyz.php");
            }

            $alert = explode(' text-center">', explode('<div class="alert alert-', $currency)[1])[0];
            if ($alert == "danger") {
                echo $WhiteTebal . "[7] Claim" . $YellowTebal . "DGB PAYU TOP " . $RedTebal . "Habis. " . $WhiteTebal . "Tunggu Besok Lagi !!!\n";
                sleep(2);
                system("php dgbXyz.php");
            } else {

                $aft = explode('">', explode('<input type="hidden" name="auto_faucet_token" value="', $currency)[1])[0];
                $ctn = explode('">', explode('<input type="hidden" name="csrf_token_name" id="token" value="', $currency)[1])[0];
                $tkn = explode('">', explode('<input type="hidden" name="token" value="', $currency)[1])[0];
                $timeClaim = explode(',', explode('let timer = ', $currency)[1])[0];
                $balance = explode('</p>', explode('<p class="lh-1 mb-1 font-weight-bold">', $currency)[1])[0];
                $totClaim = explode('</p>', explode('<p class="lh-1 mb-1 font-weight-bold">', $currency)[3])[0];

                if ($aft == "") {
                    echo $WhiteTebal . "Server Dgb Payu Top sedang " . $RedTebal . " Gangguan..!!!";
                    sleep(2);
                    echo "\r                                                                   \r";
                    sleep(2);
                    system("php dgbXyz.php");
                } else {
                    wktClaim($timeClaim);
                    sleep(2);
                    $claim = claim($aft, $ctn, $tkn, array_merge(head(), array("Referer: https://dgbpayu.top/faucet/currency/dgb")));

                    echo $WhiteTebal . "Selamat, claim " . $GreenTebal . "BERHASIL.!!!";
                    sleep(2);
                    echo "\r                                                                \r";
                    sleep(2);
                    echo $WhiteTebal . "SERVER        = " . $YellowTebal . "DGB PAYU TOP\n";
                    sleep(2);
                    echo $WhiteTebal . "Telah dikirim = " . $GreenTebal . "" . $balance . "\n";
                    sleep(2);
                    echo $WhiteTebal . "Jumlah claim  = " . $CyanTebal . "" . $totClaim . "" . $WhiteTebal . " claim\n";
                    sleep(2);
                    echo $PurpleTebal . "" . str_repeat("_", 40) . "\n";
                }
            }
        }
    }

    //AKHIR KONEKSI TERHUBUNG
} else {
    echo $WhiteTebal . "Koneksi internet " . $RedTebal . "TERPUTUS," . $WhiteTebal . " Coba periksa kembali WiFi anda..!!!";
    sleep(2);
    echo "\r                                                                                                            \r";
    sleep(2);
}
