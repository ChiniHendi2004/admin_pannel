<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Tenant extends Authenticatable
{
    use Notifiable;

    protected $table = 'tenants';

    protected $fillable = [
        'name',
        'description',
        'email',
        'password',
        'client_slug',
        'website_url',
        'employee_id',
        'academic_session',
        'expiration_date',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
