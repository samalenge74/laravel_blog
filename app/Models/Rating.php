<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    
    protected $table = 'ratings';
    
    public $fillable = ['rating', 'rateable_id', 'user_id'];
    
    /**
    * @return mixed
    */
    public function rateable()
    {
        return $this->morphTo();
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    public function post()
    {
        return $this->belongsTo(Posts::class);
    }
}
