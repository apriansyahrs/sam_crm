<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'address',
        'owner',
        'telp',
        'business_entity_id',
        'division_id',
        'region_id',
        'cluster_id',
        'district',
        'photo_shop_sign',
        'photo_front',
        'photo_left',
        'photo_right',
        'photo_ktp',
        'video',
        'limit',
        'radius',
        'latlong',
        'status',
    ];

    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'address' => 'string',
        'owner' => 'string',
        'telp' => 'string',
        'business_entity_id' => 'int',
        'division_id' => 'int',
        'region_id' => 'int',
        'cluster_id' => 'int',
        'district' => 'string',
        'photo_shop_sign' => 'string',
        'photo_front' => 'string',
        'photo_left' => 'string',
        'photo_right' => 'string',
        'photo_ktp' => 'string',
        'video' => 'string',
        'limit' => 'int',
        'radius' => 'int',
        'latlong' => 'string',
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
}
