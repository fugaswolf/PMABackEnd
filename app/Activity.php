<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
       'name','comment','status'
    ];

    public function entries(){
        return $this->hasMany(\App\Entry::class); // moet het niet: hasMany? nee, toch ewel XD mdrr
    }

    public function project() {
        return $this->belongsTo(\App\Project::class);
    }
}
