<?php

return [
	'setting' => [],
	'queue' => [
		'default' => [
			'class' => '',
			'enable' => true,
			'worker_num' => 1
		]
	],
	'connections' => [
		'rabbit_mq' => [
			'driver' => 'rabbit_mq',
			'queue' => ienv('RABBITMQ_QUEUE', 'default1212'),
			'exchange' => 'test',
			'hosts' => [
				[
					'host' => ienv('RABBITMQ_HOST', '127.0.0.1'),
					'port' => ienv('RABBITMQ_PORT', 5672),
					'user' => ienv('RABBITMQ_USER', 'guest'),
					'password' => ienv('RABBITMQ_PASSWORD', 'guest'),
					'vhost' => ienv('RABBITMQ_VHOST', '/'),
				],
			],

			'options' => [
				'ssl_options' => [
					'cafile' => ienv('RABBITMQ_SSL_CAFILE', null),
					'local_cert' => ienv('RABBITMQ_SSL_LOCALCERT', null),
					'local_key' => ienv('RABBITMQ_SSL_LOCALKEY', null),
					'verify_peer' => ienv('RABBITMQ_SSL_VERIFY_PEER', true),
					'passphrase' => ienv('RABBITMQ_SSL_PASSPHRASE', null),
				],
			]
		]
	]
];
