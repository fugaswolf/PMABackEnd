<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $primaryKey = 'id';

    // @TODO
    protected $fillable = [
        'activity_id'
    ];

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function activity(){
        return $this->belongsTo(\App\Activity::class);
    }
}
