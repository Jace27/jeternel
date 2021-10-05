<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class drugs extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        '1c_id',
        'name',
        'manufacturer'
    ];

    public function services(){
        return $this->hasMany('\App\Models\service_drugs', 'drug_id', 'id');
    }
}
