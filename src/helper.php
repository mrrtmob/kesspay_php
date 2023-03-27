<?php
require '../vendor/autoload.php';

class Helper
{
    private function toUrlParams(array $params)
    {
        $buff = "";
        foreach ($params as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }
    public function makeSign(array $params, string $key)
    {
        ksort($params);
        $signType = $params['sign_type'];
        $string = $this->toUrlParams($params);
        $string = $string . "&key=" . $key;

        // Hash string
        if ($signType == "MD5") {
            $string = md5($string);
        } else if ($signType == "HMAC-SHA256") {
        }
        return $string;
    }

    public function getToken($api_url, $client_id, $client_secret, $username, $password)
    {
        $client = new \GuzzleHttp\Client();
        $params = [
            "grant_type" => "password",
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "username" => $username,
            "password" => $password
        ];

        $response = $client->request('POST', $api_url . 'oauth/token', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($params)
        ]);
        $body = json_decode($response->getBody());
        return $body->access_token;
    }
    
    public function generateTR()
    {
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
