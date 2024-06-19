<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function businessEntity()
    {
        return $this->belongsTo(BusinessEntity::class);
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function clusters()
    {
        return $this->belongsTo(Cluster::class);
    }
}
