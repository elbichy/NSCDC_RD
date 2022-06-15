<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
    
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
    
    public function getTypeAttribute($value)
    {
        return ucwords($value);
    }

    public function documents(){
        return $this->hasMany('App\Models\Document');
    }

}
