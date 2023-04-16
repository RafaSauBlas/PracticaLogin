<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoCel extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = null;
     public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'codigocel',
        'codigocel_created_at',
        'codigocel_verified_at',
        'user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'codigocel_created_at' => 'datetime',
        'codigocel_verified_at' => 'datetime',
    ];
}
