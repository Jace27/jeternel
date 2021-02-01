<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class branches extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'address'
    ];

    public function performers(){
        return $this->hasMany('\App\Models\performers', 'branch_id', 'id');
    }
}
