<?php

namespace W7\App\Handler\Cache;

use W7\Core\Cache\Handler\HandlerAbstract;

class TestHandler extends HandlerAbstract {
	private static $data;


	public static function getHandler($config): HandlerAbstract {
		return new static();
	}

	public function set($key, $value, $ttl = null) {
		if ($ttl <= 0) {
			$ttl = 9999999999;
		}
		self::$data[$key] = [
			'value' => $value,
			'expires' => time() + $ttl
		];
	}

	public function get($key, $default = null) {
		if (!isset(self::$data[$key])) {
			return $default;
		}
		if (self::$data[$key]['expires'] < time()) {
			$this->delete($key);
			return $default;
		}
		return self::$data[$key]['value'];
	}

	public function has($key) {
		$data = $this->get($key);
		return $data !== null ? true : false;
	}

	public function setMultiple($values, $ttl = null) {
		foreach ($values as $key => $value) {
			$this->set($key, $value, $ttl);
		}
	}

	public function getMultiple($keys, $default = null) {
		$values = [];
		foreach ($keys as $index => $name) {
			$values[] = $this->get($name);
		}

		return $values;
	}

	public function delete($key) {
		unset(self::$data[$key]);
	}

	public function deleteMultiple($keys) {
		foreach ($keys as $index => $name) {
			$this->delete($name);
		}
	}

	public function clear() {
		self::$data = [];
	}
}