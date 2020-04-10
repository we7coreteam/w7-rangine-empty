<?php


namespace W7\App\Controller\User;


use W7\App\Exception\HttpErrorException;
use W7\App\Model\Entity\User\Online;
use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;

class MessageController extends ControllerAbstract {
	public function sendTo(Request $request) {
		$this->validate($request, [
			'to_uid' => 'required',
			'message' => 'required'
		]);

		$toUid = $request->post('to_uid');
		$message = $request->post('message');

		$toUser = Online::query()->where('user_id', '=', $toUid)->first();
		if (empty($toUid)) {
			throw new HttpErrorException('该用户还是你的好友');
		}
		$this->response()->withFd($toUser->fd)->withContent($message)->send();
		return 'success';
	}

	public function broadcast(Request $request) {
		$this->validate($request, [
			'message' => 'required'
		]);

		$message = $request->post('message');

		$allUser = Online::query()->get();
		foreach ($allUser as $user) {
			$this->response()->withFd($user->fd)->withContent($message)->send();
		}
		return 'success';
	}
}