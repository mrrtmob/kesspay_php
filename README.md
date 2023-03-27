# Kess payment

This documentation aims to provide all the information you need to work with Kess library.

## Installation

```bash
 composer install tmob/kesspay
```

## Usage

```php
 $kess = new \tmob\Kess();
```

Create new instance of Kess

```php
$kess = new Kess([
    "api_url" => "{api_url}",
    "username" => "{username}",
    "password" => "{password}",
    "client_id" => "{client_id}",
    "client_secret" => "{client_secret}",
    "seller_code" => "{seller_code}",
    "api_secret_key" => "{api_secret_key}",
]);
```

## Usage/Examples

Generate payment link

```php
$link = $kess=>generatePaymentLink([
        "body": "Delishop",
        "currency": "USD",
        "out_trade_no": "TR-20230310104700",
        "total_amount": 10,
        "invoke_reuse": 1
    ])
```

List all payment method

```php
$allPaymentMethod = $kess=>listAllPaymentMethod()
```

Query Order

```php
$queryOrder = $kess=>queryOrder([
	"out_trade_no": "$allPaymentMethod = $kess=>listAllPaymentMethod()
"
])

```

Close Order

```php
$queryOrder = $kess=>closeOrder([
	"out_trade_no": "$allPaymentMethod = $kess=>listAllPaymentMethod()
"
])

```

## Kess Documentation

[Documentation](https://devwebpayment.kesspay.io/docs)
