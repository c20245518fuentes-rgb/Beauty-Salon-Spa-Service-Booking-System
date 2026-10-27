<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'client_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'client_id',
        'client_name',
        'client_phone',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'client_id', 'client_id');
    }
}