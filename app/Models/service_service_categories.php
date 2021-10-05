<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service_service_categories extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'service_id',
        'category_id',
    ];

    public function service(){
        return $this->belongsTo('\App\Models\services', 'service_id', 'id');
    }
    public function category(){
        return $this->belongsTo('\App\Models\service_categories', 'category_id', 'id');
    }
}
