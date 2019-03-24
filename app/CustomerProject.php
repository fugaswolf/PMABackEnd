<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CustomerProject extends Pivot
{
    public function project()
    {
        return $this->belongsTo('App\Project');
    }
    
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
    
    public function customerproject()
    {
        return $this->hasManyThrough('App\Customer', 'App\Project');
    }
}
