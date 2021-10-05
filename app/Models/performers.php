<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class performers extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $fillable = [
        '1c_id',
        'first_name',
        'last_name',
        'second_name',
        'photo',
        'presentation',
        'type_id',
        'working_hours'
    ];

    public function branches(){
        return $this->belongsToMany('\App\Models\branches', 'performers_branches', 'performer_id', 'branch_id');
    }
    public function services(){
        return $this->belongsToMany('\App\Models\services', 'service_performers', 'performer_id', 'service_id')->withTrashed();
    }
    public function statuses(){
        return $this->hasMany('\App\Models\performers_performers_statuses', 'performer_id', 'id');
    }
    public function service_duration($service_id){
        $ret = $this->hasMany('\App\Models\service_performers', 'performer_id', 'id')->where('service_id', $service_id)->first();
        if ($ret == null)
            $ret = '';
        else
            $ret = $ret->duration;
        return $ret;
    }
    public function type(){
        return $this->belongsTo('\App\Models\performers_types', 'type_id', 'id');
    }
}
