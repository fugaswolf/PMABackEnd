<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
       'project_id','name','comment','status'
    ];
    protected $table = 'activities';
    public function user(){
        return $this->belongsTo('User');
    }


    public function activable()
    {
        return $this->morphTo();
    }
    /* public function tags(){
        return $this->belongsToMany('App\Tags','work_tags','work_id','tag_id');
        
    } */

    /* public function scopeWhereTagsLike($query, $keyword) {
        return $query->orWhereHas('tags', function($q) use($keyword) {
            $q->where('tag', 'LIKE', "%{$keyword}%");
        });
    } */
}
