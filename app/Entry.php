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

    // protected $casts = [
    //     'start_date' => 'date:yyyy-MM-ddThh:mm',
    //     'end_date' => 'date:yyyy-MM-ddThh:mm'

    // Y-m-d\TH:i:s
    // Y-m-d\TH:i:sP (waarom P?)
    // ];

    protected $casts = [
        //'start_date' => 'date:Y-m-d\TH:i:sP',
        //'end_date' => 'date:Y-m-d\TH:i:sP'
    ];

    public static function parseDuration($start, $end) {
        // parse volgens Y-m-d\TH:i:s
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
