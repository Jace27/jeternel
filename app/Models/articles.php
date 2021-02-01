<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articles extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'content',
        'section_id',
        'created_at',
        'updated_at'
    ];

    public function section(){
        return $this->belongsTo('\App\Models\articles_sections', 'section_id', 'id');
    }
}
