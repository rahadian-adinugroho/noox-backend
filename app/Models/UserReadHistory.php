<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class UserReadHistory extends Model
{
	use Traits\HasCompositePrimaryKey;

	protected $primaryKey = array('news_id', 'user_id');
	public $incrementing  = false;
	
	const CREATED_AT = 'first_read';
    const UPDATED_AT = 'last_read';

	protected $table    = 'user_read_history';

	public function user()
	{
		return $this->belongsTo('Noox\Models\User');
	}
}
