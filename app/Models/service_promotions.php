<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service_promotions extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'service_id',
        'promotion_id'
    ];

    public function service(){
        return $this->belongsTo('\App\Models\services', 'service_id', 'id');
    }
    public function promotion(){
        return $this->belongsTo('\App\Models\promotions', 'promotion_id', 'id');//->where('end', '>=', date('Y-m-d H:i:s'));
    }
}
