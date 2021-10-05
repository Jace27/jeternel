<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class performers_statuses extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];

    public function performers(){
        return $this->hasMany('\App\Models\performers_performers_statuses', 'status_id', 'id');
    }
}
