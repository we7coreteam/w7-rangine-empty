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

namespace W7\App\Model\Logic;

use W7\Core\Database\LogicAbstract;
use W7\Core\Helper\Traiter\InstanceTrait;

class UserLogic extends LogicAbstract {
	use InstanceTrait;

	private $user = [
		[
			'uid' => 1,
			'username' => 'tom',
			'password' => '123456'
		], [
			'uid' => 2,
			'username' => 'jerry',
			'password' => '123456'
		], [
			'uid' => 3,
			'username' => 'mike',
			'password' => '123456'
		],
	];

	public function getUserByUsernameAndPassword($username, $password) {
		foreach ($this->user as $userInfo) {
			if ($userInfo['username'] == $username && $userInfo['password'] == $password) {
				return $userInfo;
			}
		}
		return [];
	}
}
