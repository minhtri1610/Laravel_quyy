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

    public $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'gender',
        'address',
        'country',
        'city',
        'state',
        'email',
        'password',
        'phone',
        'birth_date',
        'nickname',
        'uid_code',
        'uid',
        'is_active',
        'date_registered',
        'qr_code',
    ];

    protected $casts = [

    ];
}
