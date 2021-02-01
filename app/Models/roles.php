<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];

    public function rights(){
        return $this->belongsToMany('\App\Models\rights', 'right_roles', 'role_id', 'right_id');
    }
    public function users(){
        return $this->hasMany('\App\Models\users', 'role_id', 'id');
    }
}
