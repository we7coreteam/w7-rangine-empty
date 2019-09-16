<?php


namespace W7\App\Controller\Content;


use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;

class IndexController extends ControllerAbstract {
	public function index(Request $request) {
		return 'get';
	}

	public function store(Request $request) {
		return 'post';
	}

	public function create(Request $request) {
		return 'create';
	}

	public function show(Request $request, $content = '') {
		return 'show';
	}

	public function update(Request $request, $content = '') {
		return 'update';
	}

	public function destroy(Request $request, $content = '') {
		return 'destroy';
	}

	public function edit(Request $request, $content = '') {
		return 'edit';
	}
}