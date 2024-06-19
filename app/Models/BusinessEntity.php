<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessEntity extends Model
{
    use HasFactory;

    protected $table = 'business_entities';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'string',
    ];

    public function divisions()
    {
        return $this->hasMany(Division::class);
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
