<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->hasMany('User', 'user_id', 'id');
    }
}
