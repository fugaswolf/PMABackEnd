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

    /**
     * Remove all user roles.
     */
    public function removeRoles() {
        // omvormen naar 1 query voor performantie
        /*
        $this->removeRole('worker');
        $this->removeRole('hr');
        $this->removeRole('consultant');
        $this->removeRole('manager');
        $this->removeRole('admin');
        */

        $this->roles()->detach();
    }
}
