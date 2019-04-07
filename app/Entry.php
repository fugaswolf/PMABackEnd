<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'activity_id', 'user_id', 'start_date', 'end_date', 'duration', 'description'
    ];

    public static function parseDuration($start, $end) {

        $duration = Carbon::parse($end)->diff(Carbon::parse($start))->format('%H:%I');

        // dd($duration);

        return $duration;
    }

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function activity(){
        return $this->belongsTo(\App\Activity::class);
    }
}
