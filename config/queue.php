<?php

return [
	'default' => ienv('QUEUE_CONNECTION', 'redis'),

	'connections' => [
		'database' => [
			'driver' => 'database',
			'table' => 'jobs',
			'queue' => 'default',
			'retry_after' => 90,
			'enable' => false,
			'worker_num' => 5
		],
		'redis' => [
			'driver' => 'redis',
			'connection' => 'default',
			'queue' => ienv('REDIS_QUEUE', 'default'),
			'retry_after' => 90,
			'block_for' => null,
			'worker_num' => 5
		],
		'rabbit_mq' => [
			'enable' => false,

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
			],
			'worker_num' => 5
		]
	],

	'failed' => [
		'driver' => ienv('QUEUE_FAILED_DRIVER', 'database'),
		'database' => ienv('DB_CONNECTION', 'default'),
		'table' => 'failed_jobs',
	]
];
