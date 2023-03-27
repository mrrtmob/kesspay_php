<?php
require '../vendor/autoload.php';
/**
 * @param api_url (string)
 * @param password (string)
 * @param client_id (string)
 * @param client_secret (string)
 * @param seller_code (string)
 * @param api_secret_key (string)
 * @param username (string)
 */
class Kess
{
    public array $settings;
    private $credential;

    function __construct($settings)
    {

        $this->settings = $settings;
        $this->credential = [
            "sign_type" => "MD5",
            "seller_code" => $this->settings['seller_code'],
            "login_type" => "ANONYMOUS",
            "api_url" => $this->settings['api_url'],
            "client_id" => $this->settings['client_id'],
            "client_secret" => $this->settings['client_secret'],
            "api_secret_key" => $this->settings['api_secret_key'],
            "password" => $this->settings['password'],
            "username" => $this->settings['username'],

        ];
    }
    /**
     * Use this service to create a preorder for your seller and deliver the payment link to a buyer to process the payment.
     *
     * @param body  Order title. (required)
     * @param out_trade_no Unique order ID. (required)
     * @param total_amount Total amount with two decimal. (required)
     * @param currency Currency code. Ex: USD or KHR. (required)
     * @param notify_url Kess will send the payment notification to the Notify URL.
     * @param expires_in expires time. Default value is 1800
     * @param detail Product detail.
     * @param detail.no Product ID. (required) 
     * @param detail.name Product Name (required) 
     * @param detail.price Unit price (required)
     * @param detail.qty Unit quantity (required)
     * @param discount Unit discount
     */
    public function generatePaymentLink(array $data)
    {
        $helper = new Helper();

        $client = new \GuzzleHttp\Client();
        if (!isset($data['out_trade_no'])) throw new Exception("out_trade_no field is required");
        if (!isset($data['body'])) throw new Exception("body field is required");
        if (!isset($data['total_amount'])) throw new Exception("total_amount field is required");
        if (!isset($data['currency'])) throw new Exception("currency field is required");

        $params = [
            "service" => "webpay.acquire.createorder",
            "out_trade_no" => $data['out_trade_no'],
            "body" => $data['body'],
            "total_amount" => $data['total_amount'],
            "currency" => $data['currency'],

        ];

        if (isset($data['notify_url'])) $params['notify_url'] = $data['notify_url'];
        if (isset($data['invoke_reuse'])) $params['invoke_reuse'] = $data['invoke_reuse'];
        if (isset($data['expires_in'])) $params['expires_in'] = $data['expires_in'];
        if (isset($data['details']))  $params['details'] = $data['details'];
        if (isset($data['setting']))  $params['setting'] = $data['setting'];

        $params = array_merge($this->credential, $params);
        $params['sign'] = $helper->makeSign($params, $this->settings['api_secret_key']);
        try {
            $token = $helper->getToken($this->settings['api_url'], $this->settings['client_id'], $this->settings['client_secret'], $this->settings['username'], $this->settings['password']);

            $response = $client->request('POST', $this->settings['api_url'] . 'api/mch/v2/gateway', [
                'headers' => ['Content-Type' => 'application/json', "Authorization" => 'Bearer ' . $token],
                'body' => json_encode($params)
            ]);

            $body = json_decode($response->getBody());
            return $body;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Use this service to create a preorder for your seller and deliver the payment link to a buyer to process the payment.
     *
     * @param body  Order title. (required)
     * @param out_trade_no Unique order ID. (required)
     * @param total_amount Total amount with two decimal. (required)
     * @param currency Currency code. Ex: USD or KHR. (required)
     * @param notify_url Kess will send the payment notification to the Notify URL.
     * @param expires_in expires time. Default value is 1800
     * @param only_deeplink Generate deeplink only. Ex: 1 or 0
     * @param is_ios_device When only_deeplink is true merchant have to pass this parameter to prove the mobile device's OS (IOS or Android). Ex: 1 or 0
     * @param detail Product detail.
     * @param detail.no Product ID. (required) 
     * @param detail.name Product Name (required) 
     * @param detail.price Unit price (required)
     * @param detail.qty Unit quantity (required)
     * @param discount Unit discount
     * 
     */
    public function nativePay(array $data)
    {
        $helper = new Helper();

        $client = new \GuzzleHttp\Client();
        if (!isset($data['out_trade_no'])) throw new Exception("out_trade_no field is required");
        if (!isset($data['body'])) throw new Exception("body field is required");
        if (!isset($data['total_amount'])) throw new Exception("total_amount field is required");
        if (!isset($data['currency'])) throw new Exception("currency field is required");
        if (!isset($data['service_code'])) throw new Exception("service_code field is required");

        $params = [
            "service" => "webpay.acquire.nativePay",
            "out_trade_no" => $data['out_trade_no'],
            "body" => $data['body'],
            "total_amount" => $data['total_amount'],
            "currency" => $data['currency'],
            "service_code" => $data['service_code'],

        ];

        if (isset($data['notify_url'])) $params['notify_url'] = $data['notify_url'];
        if (isset($data['invoke_reuse'])) $params['invoke_reuse'] = $data['invoke_reuse'];
        if (isset($data['expires_in'])) $params['expires_in'] = $data['expires_in'];
        if (isset($data['details']))  $params['details'] = $data['details'];
        if (isset($data['setting']))  $params['setting'] = $data['setting'];
        if (isset($data['only_deeplink']))  $params['only_deeplink'] = $data['only_deeplink'];
        if (isset($data['is_ios_device']))  $params['is_ios_device'] = $data['is_ios_device'];

        $params = array_merge($this->credential, $params);
        $params['sign'] = $helper->makeSign($params, $this->settings['api_secret_key']);
        try {
            $token = $helper->getToken($this->settings['api_url'], $this->settings['client_id'], $this->settings['client_secret'], $this->settings['username'], $this->settings['password']);

            $response = $client->request('POST', $this->settings['api_url'] . 'api/mch/v2/gateway', [
                'headers' => ['Content-Type' => 'application/json', "Authorization" => 'Bearer ' . $token],
                'body' => json_encode($params)
            ]);

            $body = json_decode($response->getBody());
            return $body;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function listAllPaymentMethods()
    {
        $helper = new Helper();
        $client = new \GuzzleHttp\Client();
        $params = [
            "service" => "webpay.acquire.getpaymentmethods"
        ];
        $params = array_merge($this->credential, $params);
        $params['sign'] = $helper->makeSign($params, $this->settings['api_secret_key']);


        try {
            $token = $helper->getToken($this->settings['api_url'], $this->settings['client_id'], $this->settings['client_secret'], $this->settings['username'], $this->settings['password']);

            $response = $client->request('POST', $this->settings['api_url'] . 'api/mch/v2/gateway', [
                'headers' => ['Content-Type' => 'application/json', "Authorization" => 'Bearer ' . $token],
                'body' => json_encode($params)
            ]);
            $body = json_decode($response->getBody());
            return $body;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function queryOrder(array $data)
    {
        $helper = new Helper();
        $client = new \GuzzleHttp\Client();
        if (!isset($data['out_trade_no'])) throw new Exception("out_trade_no field is required");
        $params = [
            "service" => "webpay.acquire.queryOrder",
            "out_trade_no" => $data['out_trade_no'],
        ];
        $params = array_merge($this->credential, $params);
        $params['sign'] = $helper->makeSign($params, $this->settings['api_secret_key']);


        try {
            $token = $helper->getToken($this->settings['api_url'], $this->settings['client_id'], $this->settings['client_secret'], $this->settings['username'], $this->settings['password']);

            $response = $client->request('POST', $this->settings['api_url'] . 'api/mch/v2/gateway', [
                'headers' => ['Content-Type' => 'application/json', "Authorization" => 'Bearer ' . $token],
                'body' => json_encode($params)
            ]);
            $body = json_decode($response->getBody());
            return $body;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function closeOrder(array $data)
    {
        $helper = new Helper();
        $client = new \GuzzleHttp\Client();
        if (!isset($data['out_trade_no'])) throw new Exception("out_trade_no field is required");
        $params = [
            "service" => "webpay.acquire.closeorder",
            "out_trade_no" => $data['out_trade_no'],
        ];
        $params = array_merge($this->credential, $params);
        $params['sign'] = $helper->makeSign($params, $this->settings['api_secret_key']);


        try {
            $token = $helper->getToken($this->settings['api_url'], $this->settings['client_id'], $this->settings['client_secret'], $this->settings['username'], $this->settings['password']);

            $response = $client->request('POST', $this->settings['api_url'] . 'api/mch/v2/gateway', [
                'headers' => ['Content-Type' => 'application/json', "Authorization" => 'Bearer ' . $token],
                'body' => json_encode($params)
            ]);
            $body = json_decode($response->getBody());
            return $body;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    
}
