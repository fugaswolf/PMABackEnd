<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $primaryKey = 'id';

    // dat klopt allemaal niet meer want er is geen division_id in de user tabel
    // moet ik een pivot maken ?
    // nee je moet een veld division_id zetten in die tabel XD
    // waarom weggedaan ?
    // idk mdr w8
    public function user()
    {
        return $this->hasMany('App\User', 'division_id', 'id');
    }

    // deze klopt wel
    public function customer()
    {
        return $this->hasMany('App\Customer', 'division_id', 'id');
    }

    // deze klopt niet (geen division_id in projects tabel)
    public function project()
    {
        return $this->hasMany('App\Project', 'division_id', 'id');
    }
}
