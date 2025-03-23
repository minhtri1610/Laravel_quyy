<?php

namespace App\Models\Entities;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryUser extends Model
{
    use HasFactory;
    
    public $timestamps = true;

    public $model_name = '';

    public $table = 'temporary_users';

    protected $primaryKey = 'id';     

    protected $fillable = [
        'full_name',
        'gender',
        'email',
        'phone_number',
        'birth_date',
        'province',
        'district',
        'ward',
        'address',
        'note',
        'temporary_user_id',
        'approved',
    ];

    protected $casts = [

    ];
}
