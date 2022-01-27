<?php

class getTracker
{
    private const DB_HOSTNAME = '127.0.0.1';
    private const DB_NAME = 'get_request_tracker';
    private const DB_USERNAME = 'root';
    private const DB_PASSWORD = '';

    public string $url;
    public string $referer;
    public string $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:91.0) Gecko/20100101 Firefox/91.0';
    public array $headers;

    public string $save_as = '';

    public string $curl_encoding = "gzip,deflate";
    public int $curl_connect_timeout = 8;
    public int $curl_timeout = 14;

    public bool $curl_follow_location = true;

    private int $response_code;
    private string $response_size;
    private float $connect_time;
    private int $total_time;
    private array $response_data;


    private function dbConnect(): pdo
    {
        $db = "mysql:host=" . self::DB_HOSTNAME . ";port=3306;dbname=" . self::DB_NAME . ";charset=utf8mb4";
        return new PDO($db, self::DB_USERNAME, self::DB_PASSWORD);
    }

    public function doGET(): array
    {
        if (!isset($this->url)) {
            exit;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        if (isset($this->referer)) {
            curl_setopt($curl, CURLOPT_REFERER, $this->referer);
        }
        if (isset($this->user_agent)) {
            curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agent);
        }
        if (isset($this->headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        }
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->curl_connect_timeout);
        curl_setopt($curl, CURLOPT_ENCODING, $this->curl_encoding);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->curl_timeout);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $this->curl_follow_location);

        $response_data = curl_exec($curl);

        if (curl_errno($curl) === CURLE_OPERATION_TIMEDOUT) {
            $called_request_responses = array(
                'http_code' => 408,
                'size' => null,
                'connect_time' => null,
                'total_time' => null,
                'saved_as' => $this->save_as,
                'url' => $this->url
            );
            $this->insertRequestDetails($called_request_responses);
            return $called_request_responses;
        }
        $this->response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->response_size = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        $this->connect_time = curl_getinfo($curl, CURLINFO_CONNECT_TIME);
        $this->total_time = curl_getinfo($curl, CURLINFO_TOTAL_TIME_T);

        curl_close($curl);

        if ($this->response_code === 200 && $this->save_as !== '') {
            $fp = fopen($this->save_as, 'wb');
            fwrite($fp, $response_data);
            fclose($fp);
        }

        $this->response_data = json_decode($response_data, true);

        $called_request_responses = array(
            'http_code' => $this->response_code,
            'size' => $this->response_size,
            'connect_time' => $this->connect_time,
            'total_time' => $this->total_time,
            'saved_as' => $this->save_as,
            'url' => $this->url
        );

        $this->insertRequestDetails($called_request_responses);

        return $called_request_responses;
    }

    protected function generateString(int $length = 8): string
    {
        $character_pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $char_length = strlen($character_pool);
        $the_string = '';
        for ($i = 0; $i < $length; $i++) {
            $the_string .= $character_pool[random_int(0, $char_length - 1)];
        }
        return $the_string;
    }

    private function insertRequestDetails(array $request_data): bool
    {
        $uid = $this->insertUrlIfNotExists($request_data['url']);
        $request_data['url'] = $uid;
        if ($request_data['saved_as'] === '') {
            $request_data['saved_as'] = null;
        }
        return $this->insertRequest(array_values($request_data));
    }

    private function insertRequest(array $request_data): bool
    {
        $insert = $this->dbConnect()->prepare("INSERT INTO `requests` (`response`, `size`, `connect_time`, `total_time`, `saved_as`, `url_uid`) VALUES (?, ?, ?, ?, ?, ?);");
        return $insert->execute($request_data);
    }

    private function insertUrlIfNotExists(string $url): string
    {
        $select_uid = $this->dbConnect()->prepare("SELECT `uid` FROM `request_urls` WHERE `url` = ? LIMIT 1;");
        $select_uid->execute([$url]);
        $row = $select_uid->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {//Row found
            return $row['uid'];//URL id
        }
        $uid = $this->generateString();//8 character random string
        $insert_uid = $this->dbConnect()->prepare("INSERT INTO `request_urls` (`uid`, `url`) VALUES (?, ?);");
        $insert_uid->execute([$uid, $url]);
        return $uid;
    }


}