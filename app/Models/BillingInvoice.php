<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingInvoice extends Model
{
    use HasFactory;

    const STATUS_PAID   = 'Paid';
    const STATUS_UNPAID = 'Unpaid';

    protected $table = 'billing_invoices';
    protected $primaryKey = 'invoice_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'invoice_id',
        'appointment_id',
        'total_bill',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'total_bill' => 'decimal:2',
        'paid_at'    => 'datetime',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::STATUS_PAID);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', self::STATUS_UNPAID);
    }
}