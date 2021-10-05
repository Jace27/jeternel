<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\This;

class media extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'file_name',
        'page',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function get($name){
        $entry = media::withTrashed()->where('name', '=', $name)->first();
        if ($entry == null || !file_exists($_SERVER['DOCUMENT_ROOT'].'/images/'.$entry->page.'/'.$entry->file_name)){
            if ($entry != null) $entry->forceDelete();
            return null;
        } else {
            return $entry;
        }
    }
}
