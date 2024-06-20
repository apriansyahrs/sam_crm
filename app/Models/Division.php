<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    use HasFactory;

    protected $table = 'divisions';

    protected $fillable = [
        'name',
        'business_entity_id'
    ];

    protected $casts = [
        'name' => 'string',
        'business_entity_id' => 'int',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function businessEntity(): BelongsTo
    {
        return $this->belongsTo(BusinessEntity::class);
    }

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    public function clusters(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }

}
