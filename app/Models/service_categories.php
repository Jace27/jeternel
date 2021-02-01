<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service_categories extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'parent_category_id',
        'type_id'
    ];

    public function parent(){
        return $this->belongsTo('\App\Models\service_categories', 'parent_category_id', 'id');
    }
    public function type(){
        return $this->belongsTo('\App\Models\service_categories_types', 'type_id', 'id');
    }
    public function children(){
        return $this->hasMany('\App\Models\service_categories', 'parent_category_id', 'id');
    }
    public function services(){
        return $this->belongsToMany('\App\Models\services', 'service_service_categories', 'category_id', 'service_id')->withTrashed();
    }
}
