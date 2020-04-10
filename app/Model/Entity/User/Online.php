<?php

namespace W7\App\Model\Entity\User;

use W7\Core\Database\ModelAbstract;

class Online extends ModelAbstract {
	protected $table = 'user_online';
	protected $connection = 'default';
	protected $primaryKey = 'id';
	protected $fillable = ['user_id', 'user_name', 'fd'];
	public $timestamps = false;
}
