<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service_performers extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'service_id',
        'performer_id',
        'duration'
    ];

    public function service(){
        return $this->belongsTo('\App\Models\services', 'service_id', 'id');
    }
    public function performer(){
        return $this->belongsTo('\App\Models\performers', 'performer_id', 'id');
    }
}
