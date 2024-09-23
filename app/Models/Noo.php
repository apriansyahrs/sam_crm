<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Noo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'business_entity_id',
        'division_id',
        'name',
        'address',
        'owner',
        'phone',
        'optional_phone',
        'ktp_outlet',
        'district',
        'region_id',
        'cluster_id',
        'photo_shop_sign',
        'photo_front',
        'photo_left',
        'photo_right',
        'photo_ktp',
        'video',
        'oppo',
        'vivo',
        'realme',
        'samsung',
        'xiaomi',
        'fl',
        'latlong',
        'limit',
        'status',
        'created_by',
        'rejected_at',
        'rejected_by',
        'confirmed_at',
        'confirmed_by',
        'approved_at',
        'approved_by',
        'notes',
        'tm_id',
    ];

    protected $casts = [
        'rejected_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'approved_at' => 'datetime',
        'latlong' => 'array', // Assuming latlong is stored in JSON format
        'limit' => 'integer',
        'status' => 'string',
    ];

    public function businessEntity(): BelongsTo
    {
        return $this->belongsTo(BusinessEntity::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }

    public function tm(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tm_id');
    }
}
