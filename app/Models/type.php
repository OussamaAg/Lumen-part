<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
//here we declare our type model
class type extends Model
{
    use Authenticatable, Authorizable;
    
    protected $fillable = [
        "id_type"
    ];
    
    
}