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

        $duration = Carbon::parse($start)->diff(Carbon::parse($end))->format('%H:%I');

        return $duration;
    }

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function activity(){
        return $this->belongsTo(\App\Activity::class);
    }

    public function setStartDateAttribute($start) {
        //printf("[ start mutator called ]");

        $diff = self::parseDuration($start, $this->getAttribute('end_date'));

        //var_dump($diff);

        $this->attributes['duration'] = $diff;

        $this->attributes['start_date'] = $start;
    }

    public function setEndDateAttribute($end) {
        //printf("[ end mutator called ]");

        $diff = self::parseDuration($this->getAttribute('start_date'), $end);

        //var_dump($diff);

        $this->attributes['duration'] = $diff;

        $this->attributes['end_date'] = $end;
    }
}
