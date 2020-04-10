<?php


namespace W7\App\Controller\User;


use W7\App\Exception\HttpErrorException;
use W7\App\Model\Logic\UserLogic;
use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;

class AuthController extends ControllerAbstract {

	public function login(Request $request) {
		$this->validate($request, [
			'username' => 'required',
			'password' => 'required',
		]);

		$user = UserLogic::instance()->getUserByUsernameAndPassword($request->post('username'), $request->post('password'));

		if (empty($user)) {
			throw new HttpErrorException('用户不存在');
		}

		$data = [
			'uid' => $user['uid'] ,
			'username' => $user['username'],
		];
		//登录成功后写入session信息
		$request->session->set('user', $data);





		return $data;
	}
}