<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class BrowserStats extends Model
{
    protected $table = 'system_stats';
    protected $fillable = ['id','name'];
}
