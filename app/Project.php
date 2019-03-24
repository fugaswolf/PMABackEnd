<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $fillable = ['customer_id', 'name', 'comment', 'budget', 'status'];

    public function customer(){
        return $this->belongsTo(\App\Customer::class);
    }

    public function divisions()
    {
        return $this->hasManyThrough(\App\Division::class, \App\Customer::class);
    }

    public function activities()
    {
        return $this->hasMany(\App\Activity::class);
    }
}
