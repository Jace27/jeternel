<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class branches extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        '1c_id',
        'name',
        'address',
        'isvip'
    ];

    public function performers(){
        return $this->belongsToMany('\App\Models\performers', 'performers_branches', 'branch_id', 'performer_id');
    }
}
