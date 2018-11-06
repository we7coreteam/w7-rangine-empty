<?php

return [
	'/util' => [
		'invoice' => [
			'blue' => [
				'method' => 'POST',
			],
			'red' => [
				'method' => 'POST',
			],
			'callback' => [
				'method' => 'POST',
			],
		],
	],
	'test' => [
		'index' => [
			'method' => 'POST',
			'middleware' => [
				\W7\App\Middlewares\GatewayCheckSiteMiddleware::class
			],
		]
	]
];