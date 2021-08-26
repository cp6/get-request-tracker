<?php

function callCurl(string $url, string|null $save_as = null, string $referer = 'https://google.com/'): array
{
    $db = new PDO('mysql:host=127.0.0.1;dbname=get_requester;charset=utf8mb4', 'root', '');
    $curl = curl_init();
    $headers = [
        'Accept: */*',
        'Connection: keep-alive',
        'Keep-Alive: 20',
        'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
        'Accept-Language: en-us,en;q=0.5',
        'Origin: https://www.website.com/',
        'Pragma: no-cache',
        'Cache-Control: no-cache'
    ];
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_REFERER, $referer);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:91.0) Gecko/20100101 Firefox/91.0");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_ENCODING, "gzip,deflate");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    $response_data = curl_exec($curl);
    if (curl_errno($curl) === CURLE_OPERATION_TIMEDOUT) {
        $insert = $db->prepare("INSERT INTO `tasks` (`response`, `size`, `connect_time`, `total_time`, `url`, `save_as`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?);");
        $insert->execute([408, 0, null, null, $url, $save_as, date('Y-m-d H:i:s')]);
        return array(
            'http_code' => 408,
            'message' => 'Response timed out'
        );
    }
    $http_response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $size = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $cont_t = curl_getinfo($curl, CURLINFO_CONNECT_TIME);
    $total_t = curl_getinfo($curl, CURLINFO_TOTAL_TIME_T);
    curl_close($curl);
    if ($http_response_code === 200 && isset($save_as)) {
        $fp = fopen($save_as, 'wb');
        fwrite($fp, $response_data);
        fclose($fp);
    }
    $insert = $db->prepare("INSERT INTO `tasks` (`response`, `size`, `connect_time`, `total_time`, `url`, `save_as`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?);");
    $insert->execute([$http_response_code, $size, $cont_t, $total_t, $url, $save_as, date('Y-m-d H:i:s')]);
    return array(
        'http_code' => $http_response_code,
        'size' => $size,
        'connect_time' => $cont_t,
        'total_time' => $total_t,
        'saved_as' => $save_as
    );
}