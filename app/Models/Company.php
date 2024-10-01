<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Company extends Authenticatable

{
    use HasFactory;

    
    protected $fillable = [
        'name',
        'postal_code',
        'address',
        'representative',
        'establishment_date',
        'capital',
        'business',
        'number_of_employees',
    ];


}
