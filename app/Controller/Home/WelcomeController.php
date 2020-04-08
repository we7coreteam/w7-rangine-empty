<?php
/**
 * This file is part of Rangine
 *
 * (c) We7Team 2019 <https://www.rangine.com/>
 *
 * document http://s.w7.cc/index.php?c=wiki&do=view&id=317&list=2284
 *
 * visited https://www.rangine.com/ for more details
 */

namespace W7\App\Controller\Home;


use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\File\File;
use W7\Http\Message\Server\Request;

class WelcomeController extends ControllerAbstract {
	/**
	 * 访问URL http://127.0.0.1:88/
	 */
	public function index(Request $request) {
		return $this->render('index');
	}

	/**
	 * 文档说明 https://wiki.w7.cc/chapter/1?id=205#_36
	 */
	public function apiGet(Request $request, int $id = 0) {
		if (!empty($id)) {
			return ['id' => $id];
		}
		return $request->getQueryParams() ?: 'success';
	}

	public function testDownloadFile(Request $request) {
		return $this->response()->withHeaders([
			'Content-Type' => 'application/x-zip',
			'Content-Disposition' => "attachment; filename*=UTF-8''123.zip",
			'Pragma' => 'public',
			'Cache-Control' => 'public, must-revalidate',
			'Content-Transfer-Encoding' => 'binary'
		])->withFile(new File(RUNTIME_PATH . '/123.zip'))->send();
	}

	public function testWrite(Request $request) {
		for($i=0;$i<1000;$i++){
			$this->response()->withContent($i.'</br>')->write();
			sleep(1);
		}
		return 'success';
	}
}