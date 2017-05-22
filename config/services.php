<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

	'gmail' => [
		'host' => env('LAXUS_MAIL_HOST'),
		'port' => env('LAXUS_MAIL_PORT'),
		'username' => env('LAXUS_MAIL_USERNAME'),
		'password' => env('LAXUS_MAIL_PASSWORD'),
		'encryption' => env('LAXUS_MAIL_ENCRYPTION'),
		'from' => [
			'address' => env('LAXUS_MAIL_USERNAME'),
			'name' => 'Flinnt',
		],
	],

	'mobisoft' => [
		'general' => [
			'username' => env('MOBISOFT_GENERAL_USER'),
			'password' => env('MOBISOFT_GENERAL_PASSWORD'),
			'gsm' => env('MOBISOFT_GENERAL_GSM_ID'),
			'url' => env('MOBISOFT_GENERAL_URL')
		],
		'verify' => [
			'username' => env('MOBISOFT_VERIFY_USER'),
			'password' => env('MOBISOFT_VERIFY_PASSWORD'),
			'gsm' => env('MOBISOFT_VERIFY_GSM_ID'),
			'url' => env('MOBISOFT_VERIFY_URL')
		]
	],

	'twilio' => [
		'sid' => 'AC7fc3a8f4160631e676862f4960559985',
		'authtoken' => '974344a7fef03230efa03a33a7304c96',
		'from' => '1234567890'
	],

];
