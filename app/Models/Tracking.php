<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tracking';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'event',
        'timestamp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'timestamp' => 'datetime',
    ];
}