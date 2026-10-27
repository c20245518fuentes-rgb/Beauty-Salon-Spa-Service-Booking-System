<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $primaryKey = 'service_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'service_id',
        'service_name',
        'service_category',
        'service_duration_min',
        'price',
    ];

    protected $casts = [
        'service_duration_min' => 'integer',
        'price'                => 'decimal:2',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'service_id', 'service_id');
    }
}