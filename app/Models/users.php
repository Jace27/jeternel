<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'phone',
        'password',
        'role_id',
        'first_name',
        'last_name',
        'third_name'
    ];

    public function role(){
        return $this->belongsTo('\App\Models\roles', 'role_id', 'id');
    }
}
