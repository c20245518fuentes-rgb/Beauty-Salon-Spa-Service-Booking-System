<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    // Status constants — avoids magic strings everywhere
    const STATUS_PENDING    = 'Pending';
    const STATUS_CONFIRMED  = 'Confirmed';
    const STATUS_COMPLETED  = 'Completed';
    const STATUS_CANCELLED  = 'Cancelled';
    const STATUS_NO_SHOW    = 'No Show';

    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'appointment_id',
        'client_id',
        'stylist_id',
        'service_id',
        'appointment_datetime',
        'appointment_status',
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
    ];

    /* ---------- Relationships ---------- */

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    public function stylist(): BelongsTo
    {
        return $this->belongsTo(Stylist::class, 'stylist_id', 'stylist_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(BillingInvoice::class, 'appointment_id', 'appointment_id');
    }

    /* ---------- Scopes ---------- */

    public function scopePending($query)
    {
        return $query->where('appointment_status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('appointment_status', self::STATUS_COMPLETED);
    }
}