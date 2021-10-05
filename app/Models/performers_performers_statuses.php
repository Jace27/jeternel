<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class performers_performers_statuses extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $dates = ['start', 'end'];
    protected $fillable = [
        'performer_id',
        'status_id',
        'start',
        'end'
    ];

    public function performer(){
        return $this->belongsTo('\App\Models\performers', 'performer_id', 'id');
    }
    public function status(){
        return $this->belongsTo('\App\Models\performers_statuses', 'status_id', 'id');
    }
}
