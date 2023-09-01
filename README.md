# package-winipayer-php

    //** Simple creation of an invoice **

    $winipayer = new Package\Winipayer();

    echo $winipayer->createInvoice($amount, $description, $currency);

    //** Complex creation of an invoice **

    $winipayer = new Package\Winipayer();

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
