<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function businessEntity()
    {
        return $this->belongsTo(BusinessEntity::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function clusters()
    {
        return $this->belongsTo(Cluster::class);
    }
}
