<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service_drugs extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'service_id',
        'drug_id',
        'using_volume',
        'comment'
    ];

    public function service(){
        return $this->belongsTo('\App\Models\services', 'service_id', 'id');
    }
    public function drug(){
        return $this->belongsTo('\App\Models\drugs', 'drug_id', 'id');
    }
}
