<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';

    protected $fillable = [
        'name',
        'business_entity_id',
        'division_id'
    ];

    protected $casts = [
        'name' => 'string',
        'business_entity_id' => 'int',
        'division_id' => 'int',
    ];

    public function users(): HasMany
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

    public function clusters(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }
}
