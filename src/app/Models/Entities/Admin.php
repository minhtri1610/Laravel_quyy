<?php

namespace App\Models\Entities;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    
    public $timestamps = true;

    public $model_name = '';

    protected $guard = 'admin';

    public $table = 'admins';

    protected $primaryKey = 'id';     

    protected $fillable = [

    ];

    protected $casts = [
        'roles' => 'array',
    ];

    public function hasRole($role)
    {
        return in_array($role, $this->roles ?? []);
    }
}
