<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service_other_names extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'service_id',
        'other_name'
    ];

    public function service(){
        return $this->belongsTo('\App\Models\services', 'service_id', 'id');
    }
}
