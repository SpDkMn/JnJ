<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'dni', 'username', 'email', 'password', 'profile_id',
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function profile()
    {
        return $this->belongsTo('App\Profile', 'profile_id', 'id');
    }

    public function representante()
    {
        return $this->hasOne('App\Representante','user_id','id');
    }

    public function ejecutivo()
    {
        return $this->hasOne('App\Ejecutivo','user_id','id');
    }

}
