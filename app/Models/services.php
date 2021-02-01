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
        'name',
        'description',
        'preparation',
        'rehabilitation',
        'contraindications',
        'duration',
        'course',
        'cost',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function other_names(){
        return $this->hasMany('\App\Models\service_other_names', 'service_id', 'id');
    }
    public function drugs(){
        return $this->hasMany('\App\Models\service_drugs', 'service_id', 'id');
    }
    public function performers(){
        return $this->belongsToMany('\App\Models\performers', 'service_performers', 'service_id', 'performer_id');
    }
    public function categories(){
        return $this->belongsToMany('\App\Models\service_categories', 'service_service_categories', 'service_id', 'category_id');
    }
}
