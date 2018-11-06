<?php
/**
 * 公共函数
 * @author donknap
 * @date 18-8-28 下午2:43
 */

/**
 * 截取字符串
 * @param $string
 * @param $length
 * @param int $havedot
 * @param string $charset
 * @return mixed|string
 */
function icutstr($string, $length, $havedot = 0, $charset = '') {
	global $_W;
	if (empty($charset)) {
		$charset = $_W['charset'];
	}
	if (strtolower($charset) == 'gbk') {
		$charset = 'gbk';
	} else {
		$charset = 'utf8';
	}
	if (istrlen($string, $charset) <= $length) {
		return $string;
	}
	if (function_exists('mb_strcut')) {
		$string = mb_substr($string, 0, $length, $charset);
	} else {
		$pre    = '{%';
		$end    = '%}';
		$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);

		$strcut = '';
		$strlen = strlen($string);

		$n = $tn = $noc = 0;
		if ($charset == 'utf8') {
			while ($n < $strlen) {
				$t = ord($string[$n]);
				if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1;
					$n++;
					$noc++;
				} elseif (194 <= $t && $t <= 223) {
					$tn = 2;
					$n  += 2;
					$noc++;
				} elseif (224 <= $t && $t <= 239) {
					$tn = 3;
					$n  += 3;
					$noc++;
				} elseif (240 <= $t && $t <= 247) {
					$tn = 4;
					$n  += 4;
					$noc++;
				} elseif (248 <= $t && $t <= 251) {
					$tn = 5;
					$n  += 5;
					$noc++;
				} elseif ($t == 252 || $t == 253) {
					$tn = 6;
					$n  += 6;
					$noc++;
				} else {
					$n++;
				}
				if ($noc >= $length) {
					break;
				}
			}
			if ($noc > $length) {
				$n -= $tn;
			}
			$strcut = substr($string, 0, $n);
		} else {
			while ($n < $strlen) {
				$t = ord($string[$n]);
				if ($t > 127) {
					$tn = 2;
					$n  += 2;
					$noc++;
				} else {
					$tn = 1;
					$n++;
					$noc++;
				}
				if ($noc >= $length) {
					break;
				}
			}
			if ($noc > $length) {
				$n -= $tn;
			}
			$strcut = substr($string, 0, $n);
		}
		$string = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	}

	if ($havedot) {
		$string = $string . "...";
	}

	return $string;
}

function istrlen($string, $charset = '') {
	$charset = strtolower($charset);
	if ($charset == 'gbk') {
		$charset = 'gbk';
	} else {
		$charset = 'utf-8';
	}
	if (function_exists('mb_strlen')) {
		return mb_strlen($string, $charset);
	} else {
		$n      = $noc = 0;
		$strlen = strlen($string);
		if ($charset == 'utf-8') {
			while ($n < $strlen) {
				$t = ord($string[$n]);
				if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$n++;
					$noc++;
				} elseif (194 <= $t && $t <= 223) {
					$n += 2;
					$noc++;
				} elseif (224 <= $t && $t <= 239) {
					$n += 3;
					$noc++;
				} elseif (240 <= $t && $t <= 247) {
					$n += 4;
					$noc++;
				} elseif (248 <= $t && $t <= 251) {
					$n += 5;
					$noc++;
				} elseif ($t == 252 || $t == 253) {
					$n += 6;
					$noc++;
				} else {
					$n++;
				}
			}
		} else {
			while ($n < $strlen) {
				$t = ord($string[$n]);
				if ($t > 127) {
					$n += 2;
					$noc++;
				} else {
					$n++;
					$noc++;
				}
			}
		}
		return $noc;
	}
}