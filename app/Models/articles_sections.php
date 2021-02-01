<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articles_sections extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'parent_section_id'
    ];

    public function parent(){
        return $this->belongsTo('\App\Models\articles_sections', 'parent_section_id', 'id');
    }
    public function children(){
        return $this->hasMany('\App\Models\articles_sections', 'parent_section_id', 'id');
    }
    public function articles(){
        return $this->hasMany('\App\Models\articles', 'section_id', 'id');
    }
}
