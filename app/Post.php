<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	// Table Name
    protected $table = 'posts';

    public $primaryKey = 'id';

    public $timestamp = true; 

    public function user() {
    	return $this->belongsTo('App\User');
    }
}
