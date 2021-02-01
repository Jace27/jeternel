<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class news extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'title',
        'content',
        'is_important',
        'created_at',
        'updated_at'
    ];
}
