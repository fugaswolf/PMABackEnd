<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'division_id', 'name', 'comment', 'country', 'address', 'phone', 'email', 'status',
    ];

    public function projects(){
        return $this->hasMany('Project');
    }


}
