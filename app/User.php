<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
//here we declare our user model 

class user extends Model implements JWTSubject
{
    use Authenticatable, Authorizable;

    
    protected $fillable = [
        "id","email","password","id_type"
    ];
    //here are the functions for adding or updating a user 
public function addUser(User $id_user)
    {
        return $this->users()->save($id_user);
    }
    
    public function deleteUser($id_user)
    {
        $this->users()->find($id_user)->delete();
        return ["message"=>"The user has been deleted"];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}