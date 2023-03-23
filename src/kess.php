<?php
require '../vendor/autoload.php';
require './kessPay.php';
require './helper.php';
$webpay = new WebPay([
    "api_url" => "https://devwebpayment.kesspay.io/",
    "username" => "delishop@kesspay.io",
    "password" => "yrwGSzFPhHaDJSv1gfLA4WlVatNAjD3Ohea63ZlwHBPld",
    "client_id" => "17546a0c-e0f8-44e5-a3a9-569f9995f79f",
    "client_secret" => "5dscy7pEyBFVsXkwRfguAwoQiHSjALk91fPkt1w9r0qbK",
    "seller_code" => "CU2211-27962254758514976",
    "api_secret_key" => "mSZvRWpSTZQwOhfvSlW2iSZrHi8D0D067nBHplGmd2U4V",
]);

var_dump($webpay->generatePaymentLink([
    "out_trade_no"=> "fsdfdsff",
    "body" => "Delishop",
    "currency" => "USD",
    "total_amount" => 10,
    "invoke_reuse" => 1,
    "setting" => [
        "enabled_payment_methods" => ["VISA_MASTER"]
    ]
]));
