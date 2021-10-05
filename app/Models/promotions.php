<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class promotions extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'title',
        'banner_file',
        'description',
        'start',
        'end'
    ];

    public function services(){
        return $this->hasMany('\App\Models\service_promotions', 'promotion_id', 'id');
    }
}
