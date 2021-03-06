<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use willvincent\Rateable\Rateable;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;
    use rateable;
    
    // restricts columns from modifying
    protected $guarded = [];
    
    // posts has many comments
    // returns all comments on a post
    public function comments() {
        return $this->hasMany('App\Models\Comments', 'on_post');
    }
    
    // return an instance of the user who is the author of the post
    public function author() {
        return $this->belongsTo('App\Models\User', 'author_id');
    }
    
    public function rating(){
        return $this->hasMany(Rating::class, 'rateable_id');
    }
}
