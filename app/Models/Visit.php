<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'visit_date',
        'user_id',
        'outlet_id',
        'visit_type',
        'latlong_in',
        'latlong_out',
        'check_in_time',
        'check_out_time',
        'visit_report',
        'transaction',
        'visit_duration',
        'picture_visit_in',
        'picture_visit_out',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'latlong_in' => 'array',  // Assuming it's stored as JSON or similar format
        'latlong_out' => 'array', // Assuming it's stored as JSON or similar format
        'transaction' => 'boolean',
        'visit_duration' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
