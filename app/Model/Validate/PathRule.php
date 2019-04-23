<?php
/**
 * 扩展验证规则
 * @author donknap
 * @date 19-4-20 下午3:07
 */

namespace W7\App\Model\Validate;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class PathRule implements Rule {
	public function passes($attribute, $value) {
		if (Str::startsWith($value, ['..', '..\\', '\\\\' ,'\\', '..\\\\'])) {
			return false;
		} else {
			return true;
		}
	}

	public function message() {
		return 'Invalid path';
	}
}