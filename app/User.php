<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    // dit zal mss alles breken
    protected $with = [
        'roles'
    ];

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'division_id', 
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function division()
    {

        $result = static::newQuery()
            ->rightJoin('entries', 'entries.user_id', '=', 'users.id')
            ->rightJoin('activities', 'activities.id', '=', 'entries.activity_id')
            ->rightJoin('projects', 'projects.id', '=', 'activities.project_id')
            ->rightJoin('customers', 'customers.id', '=', 'projects.customer_id')

            ->rightJoin('divisions', 'divisions.id', '=', 'customers.division_id')
            ->select('divisions.*')
            ->where('users.id', '=', $this->getAttribute('id'))
            ->firstOrFail()
            ->toArray();

        return $result;
    }

    protected static function makeAdmin($user){
        $user->assignRole('admin');
        unmakeConsultant($user);
        unmakeHR($user);
        unmakeManager($user);
        unmakeWorker($user);
    }

    protected static function makeConsultant($user){
        $user->assignRole('consultant');
        unmakeAdmin($user);
        unmakeHR($user);
        unmakeManager($user);
        unmakeWorker($user);
    }

    protected static function makeHR($user){
        $user->assignRole('hr');
        unmakeConsultant($user);
        unmakeAdmin($user);
        unmakeManager($user);
        unmakeWorker($user);
    }

    protected static function makeManager($user){
        $user->assignRole('manager');
        unmakeConsultant($user);
        unmakeHR($user);
        unmakeAdmin($user);
        unmakeWorker($user);
    }
    protected static function makeWorker($user){
        $user->assignRole('worker');
        unmakeConsultant($user);
        unmakeHR($user);
        unmakeManager($user);
        unmakeAdmin($user);
    }

    protected static function unmakeAdmin($user){
        if($user->hasRole('admin')){
            $user->removeRole('admin');
        }

        return;
    }
    protected static function unmakeConsultant($user){
        if($user->hasRole('consultant')){
            $user->removeRole('consultant');
        }

        return;
    }
    protected static function unmakeHR($user){
        if($user->hasRole('hr')){
            $user->removeRole('hr');
        }

        return;
    }
    protected static function unmakeManager($user){
        if($user->hasRole('manager')){
            $user->removeRole('manager');
        }

        return;
    }
    protected static function unmakeWorker($user){
        if($user->hasRole('worker')){
            $user->removeRole('worker');
        }

        return;
    }

    protected function activities() {

        return $this->hasManyThrough(\App\Activity::class, \App\Entry::class);
    }


    // Password hashen bij het wijzigen van de password
    protected function setPasswordAttribute($value) 
    { 
        $this->attributes['password'] = \Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function projects() {
        return $this->hasManyThrough(\App\Project::class, \App\Entry::class);
    }

    public function entries() {
        return $this->hasMany(\App\Entry::class);
    }
}
