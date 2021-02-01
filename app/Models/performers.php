<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class performers extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $fillable = [
        'first_name',
        'last_name',
        'third_name',
        'photo',
        'specialization',
        'experience',
        'branch_id'
    ];

    public function branch(){
        return $this->belongsTo('\App\Models\branches', 'branch_id', 'id');
    }
    public function services(){
        return $this->belongsToMany('\App\Models\services', 'service_performers', 'performer_id', 'service_id')->withTrashed();
    }
    public function service_duration($service_id){
        $ret = $this->hasMany('\App\Models\service_performers', 'performer_id', 'id')->where('service_id', $service_id)->first();
        if ($ret == null)
            $ret = '';
        else
            $ret = $ret->duration;
        return $ret;
    }
}
