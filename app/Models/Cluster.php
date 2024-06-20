<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_entity_id',
        'division_id',
        'region_id',
    ];

    protected $casts = [
        'name' => 'string',
        'business_entity_id' => 'int',
        'division_id' => 'int',
        'region_id' => 'int',
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

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

}
