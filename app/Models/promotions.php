<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class promotions extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'type_id',
        'title',
        'banner_file',
        'description',
        'start',
        'end'
    ];

    public function type(){
        return $this->belongsTo('\App\Models\promotions_types', 'type_id', 'id');
    }
}
