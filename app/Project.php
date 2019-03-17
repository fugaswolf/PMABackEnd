<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $fillable = ['customer_id ', 'name', 'comment', 'budget', 'status'];

    /**
     * Define relation between Project and Client.
     *
     * @return Eloquent\Model
     */
    public function customer()
    {
        return $this->belongsTo('Customer');
    }
   
    /**
     * A project will have activities
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */

    public function customers(){
        return $this->belongsTo('Customer','customer_id','id');
    }
}
