<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class SystemStats extends Model
{
    protected $table = 'system_stats';
    protected $fillable = ['id','name'];
}
