<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EntryUser extends Pivot
{
    public function entry()
    {
        return $this->belongsTo('App\Entry');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function entryuser()
    {
        return $this->hasManyThrough('App\User', 'App\Entry');
    }
   
}
