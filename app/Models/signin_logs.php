<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class signin_logs extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'time'
    ];

    public function user(){
        return $this->belongsTo('\App\Models\users', 'user_id', 'id');
    }
}
