<?php

namespace App\Models\Entities;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    
    protected $guard = 'users';
    
    public $timestamps = true;

    public $model_name = '';

    public $table = '';

    protected $primaryKey = '';     

    protected $fillable = [

    ];

    protected $casts = [

    ];
}
