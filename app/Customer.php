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


    public function project(){
        return $this->belongsTo('App\Customer','customer_project','customer_id','project_id');
        
    }

    public function division()
    {
        return $this->belongsTo(\App\Division::class);
    }
}
