<?php
/**
 * @author donknap
 * @date 19-4-23 下午6:38
 */

namespace W7\App\Model\Entity\Home;


use W7\Core\Database\ModelAbstract;

class Home extends ModelAbstract {
	public $timestamps = false;
	protected $table = 'home';
	protected $primaryKey = 'id';
	protected $fillable = [];

}