<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class RegularHoliday extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'day',
        'day_index',
    ];

    public function restaurants(){
        return $this->belongsToMany(Restaurant::class)->withtimestamps();
    }


}