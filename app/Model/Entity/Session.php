<?php

namespace W7\App\Model\Entity;

use W7\Core\Database\ModelAbstract;

class Session extends ModelAbstract {
	protected $table = 'session';
	protected $connection = 'default';
	protected $primaryKey = 'id';
	protected $fillable = ['session_id', 'data', 'expired_at'];
	public $timestamps = false;
}
