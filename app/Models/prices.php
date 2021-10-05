<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prices extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nonvip_low',
        'nonvip_high',
        'vip_low',
        'vip_high'
    ];

    public function service(){
        return $this->belongsTo('\App\Models\services', 'price_id', 'id');
    }
}
