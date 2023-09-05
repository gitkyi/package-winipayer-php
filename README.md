PHP package to create and verify payments with WiniPayer.

## Support us

Documentation link : https://winipayer.com/developpeur/documentation

[<img src="https://checkout.winipayer.com/dist/img/logo-winipayer.png?t=1" width="419px" />](https://www.winipayer.com)

## Installation

You can install the package via composer:

```bash
composer require winipayer/winipayerphpsdk
```

## Usage

```php

    //** Simple creation of an invoice **

    $winipayer = new Winipayer("test", "qgK1LspWt15KXzx273", "a20301ed-ad42-42c2-9ecd-da88b2bced", "783a8aeb5a9f4664b8c8d41595c94f");

    echo $winipayer->createInvoice(1000, 'Description de l\article');

    //** Complex creation of an invoice **

    $winipayer = new Winipayer("test", "qgK1LspWt15KXzx273", "a20301ed-ad42-42c2-9ecd-da28b2bced", "783a8aeb5a9f4664c198d41595c94f");

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
    ->setCancelUrl('https://tester.winipayer.com/cancel')
    ->setReturnUrl('https://tester.winipayer.com/success')
    ->setCallbackUrl('https://tester.winipayer.com/callback')
    ->createInvoice(1000, "Juste un test");

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
