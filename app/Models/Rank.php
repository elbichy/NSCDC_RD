<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    protected $guarded = [];

    public function users(){
        return $this->hasMany('App\User', 'gl', 'gl');
    }
}
