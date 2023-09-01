This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://www.winibuilder.com/file/project/wb100023/config/72150d38-a6ad-4ed8-9244-621b380f70a4.png?t=1" width="419px" />](https://www.winipayer.com)

## Installation

You can install the package via composer:

"require": {
"php": "^7.4|^8.0"
}

```bash
composer require package/winipayer
```

## Usage

```php

    //** Simple creation of an invoice **

    $winipayer = new Winipayer();

    echo $winipayer->createInvoice($amount, $description, $currency);

    //** Complex creation of an invoice **

    $winipayer = new Winipayer();

    echo $winipayer->setEndpoint('link_your_end_point')
    ->setItems(
        [
            [
                'name' => 'Pot de fleure',
                'quantity' => 2,
                'unit_price' => 3650,
                'total_price' => 7300
            ]
        ],
        ...
    )
    ->setWpsecure('true')// true or false
    ->setChannel(['mtn-cote-divoire','orange-cote-divoire']) // ['orange-cote-divoire','mtn-cote-divoire','wave-cote-divoire','stripe','cinetpay-ml','cinetpay-sn','cinetpay-tg','cinetpay-bf','cinetpay-bj','cinetpay-ne']
    ->setCustomerOwner(['uuid_or_id_owner'])
    ->setStore(
        [
            'name' => 'Store',
            'description' => 'description',
            'web_url' => 'your_link_web_site',
            'logo_url' => 'link_logo_web_store',
            'email' => 'your_email',
            'phone' => 'your_number_phone',
            'country' => 'your_sigle_country',
            'city' => 'your_city',
            'address' => 'your_address',
            'ipn_url' => 'your_link/ipn.php'
        ]
    )
    ->setCustomData(
        [
           'client_id' => 'client_id',
           'order_uuid' => Str::uuid()->__toString(),
        ]
    )
    ->setCancelUrl('https://tester.winipayer.com')
    ->setReturnUrl('https://tester.winipayer.com/success')
    ->setCallbackUrl('https://tester.winipayer.com/ipn')
    ->createInvoice($amount, $description, $currency);

```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-    [Kouakou Yao InnoCent](https://github.com/gitkyi)
-    [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
