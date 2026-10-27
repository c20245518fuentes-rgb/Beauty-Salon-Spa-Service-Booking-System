<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stylist extends Model
{
    use HasFactory;

    protected $table = 'stylists';
    protected $primaryKey = 'stylist_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'stylist_id',
        'stylist_name',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'stylist_id', 'stylist_id');
    }
}