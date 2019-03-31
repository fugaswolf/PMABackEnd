<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $primaryKey = 'id';

    // @TODO
    protected $fillable = [
        'activity_id', 'user_id', 'start_date', 'end_date', 'duration', 'description'
    ];

    public function duration($start, $end){
        $duration = $end->diff($start)->format('%H:%I:%s');

        $this->attributes['duration'] = $duration;
    }

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function activity(){
        return $this->belongsTo(\App\Activity::class);
    }
}
