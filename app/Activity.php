<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
       'name','comment','status', 'project_id'
    ];

    public function entries(){
        return $this->hasMany(\App\Entry::class);
    }

    public function project() {
        return $this->belongsTo(\App\Project::class);
    }
}
