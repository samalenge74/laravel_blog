<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    // user has many posts
    public function posts()
    {
        return $this->hasMany('App\Models\Posts', 'author_id');
    }
    
    // user has many comments
    public function comments(){
        return $this->hasMany('App\Models\Comments','from_user');
    }
    
    // is user allowed to post
    public function can_post() {
        $role = $this->role;
        if($role == 'author' || $role == 'admin'){
            return true;
        }
        return false;
    }
    
    public function is_admin()
    {
        $role = $this->role;
        if ($role == 'admin') {
            return true;
        }
        return false;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
