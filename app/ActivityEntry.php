<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ActivityEntry extends Pivot
{
    public function entry()
    {
        return $this->belongsTo('App\Entry');
    }
    
    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }
    
    public function activityentry()
    {
        return $this->hasManyThrough('App\Activity', 'App\Entry');
    }
}