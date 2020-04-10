<?php


namespace W7\App\Model\Logic;


use W7\Core\Database\LogicAbstract;
use W7\Core\Helper\Traiter\InstanceTraiter;

class UserLogic extends LogicAbstract {
	use InstanceTraiter;

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