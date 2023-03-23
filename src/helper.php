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
}
