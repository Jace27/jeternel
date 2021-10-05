<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class services extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        '1c_id',
        'name',
        'description',
        'preparation',
        'rehabilitation',
        'indications',
        'contraindications',
        'course',
        'price_id',
        'instruct1',
        'instruct2',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function price(){
        return $this->hasOne('\App\Models\prices', 'id', 'price_id');
    }
    public function other_names(){
        return $this->hasMany('\App\Models\service_other_names', 'service_id', 'id');
    }
    public function drugs(){
        return $this->hasMany('\App\Models\service_drugs', 'service_id', 'id');
    }
    public function performers(){
        return $this->hasMany('\App\Models\service_performers', 'service_id', 'id');
    }
    public function categories(){
        return $this->belongsToMany('\App\Models\service_categories', 'service_service_categories', 'service_id', 'category_id');
    }
    public function promotions(){
        return $this->hasMany('\App\Models\service_promotions', 'service_id', 'id');
    }
}
