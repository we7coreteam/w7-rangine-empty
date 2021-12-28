<?php

/**
 * WeEngine Api System
 *
 * (c) We7Team 2019 <https://www.w7.cc>
 *
 * This is not a free software
 * Using it under the license terms
 * visited https://www.w7.cc for more details
 */

namespace W7\App\Handler\Mqtt;

use Simps\MQTT\Message\AbstractMessage;
use W7\App;
use W7\Http\Message\Server\Request;
use W7\Mqtt\Handler\HandlerInterface;
use W7\Mqtt\Message\AuthMessage;
use W7\Mqtt\Message\ConnAckMessage;
use W7\Mqtt\Message\PubAckMessage;
use W7\Mqtt\Message\PubCompMessage;
use W7\Mqtt\Message\PublishMessage;
use W7\Mqtt\Message\PubRecMessage;
use W7\Mqtt\Message\PubRelMessage;
use W7\Mqtt\Message\SubAckMessage;
use W7\Mqtt\Message\UnSubAckMessage;

class MessageHandler implements HandlerInterface {
	public function onMqConnect(Request $request): AbstractMessage {
		return (new ConnAckMessage())->setCode(0)
			->setSessionPresent(0);
	}

	public function onAuth(Request $request): AuthMessage {
		// TODO: Implement onAuth() method.
	}

	public function onMqDisconnect(Request $request): bool {
		return true;
	}

	public function onMqPing(Request $request): bool {
		return true;
	}

	public function onMqPublish(Request $request) {
		$data = $request->post();
		foreach (App::$server->getServer()->connections as $sub_fd) {
			App::$server->getServer()->send(
				$sub_fd,
				(new PublishMessage())->setTopic($data['topic'])
					->setMessage($data['message'])
					->setDup($data['dup'])
					->setQos($data['qos'])
					->setRetain($data['retain'])
					->setMessageId($data['message_id'] ?? 0)
			);
		}

		if ($data['qos'] === 1) {
			return (new PubAckMessage())->setCode(0)->setMessageId($data['message_id'] ?? '');
		}

		if ($data['qos'] === 2) {
			return (new PubRecMessage())->setCode(0)->setMessageId($data['message_id'] ?? '');
		}
	}

	public function onMqPublishRec(Request $request): PubRelMessage {
		$data = $request->post();
		return (new PubRelMessage())->setMessageId($data['message_id'] ?? '');
	}

	public function onMqPublishRel(Request $request): PubCompMessage {
		$data = $request->post();
		return (new PubCompMessage())->setMessageId($data['message_id'] ?? '');
	}

	public function onMqSubscribe(Request $request): SubAckMessage {
		$data = $request->post();
		foreach ($data['topics'] as $k => $qos) {
			if (is_numeric($qos) && $qos < 3) {
				$payload[] = $qos;
			} else {
				$payload[] = 0x80;
			}
		}

		return (new SubAckMessage())->setMessageId($data['message_id'] ?? '')
			->setCodes($payload);
	}

	public function onMqUnSubscribe(Request $request): UnSubAckMessage {
		$data = $request->post();
		return  (new UnSubAckMessage())->setMessageId($data['message_id'] ?? '');
	}
}
