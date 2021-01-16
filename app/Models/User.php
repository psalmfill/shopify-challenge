<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'api_secret_key',
        'api_public_key',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // generate a unique api public key and secret key for user
        static::creating(function($user){
            $user->api_public_key = 'IR-PKEY-'.Str::random();
            $user->api_secret_key = 'IR-SKEY-'.Str::random();
        });

    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
   
}
