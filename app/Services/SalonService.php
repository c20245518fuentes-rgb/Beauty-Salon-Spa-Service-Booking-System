<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\BillingInvoice;
use Illuminate\Support\Facades\DB;

class SalonService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function createAppointment(array $data): Appointment
    {
        $data = $this->sanitizeData($data);

        return DB::transaction(function () use ($data): Appointment {
            return Appointment::query()->create($data);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateAppointment(string $id, array $data): Appointment
    {
        $data = $this->sanitizeData($data);

        return DB::transaction(function () use ($id, $data): Appointment {
            $appointment = Appointment::query()->whereKey($id)->firstOrFail();
            $appointment->update($data);
            $appointment->load('service', 'invoice');

            if ($appointment->invoice !== null) {
                $appointment->invoice->update([
                    'total_bill' => $this->formatAmount($appointment->service->price),
                ]);
            }

            return $appointment;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createInvoice(array $data): BillingInvoice
    {
        $data = $this->sanitizeData($data);

        return DB::transaction(function () use ($data): BillingInvoice {
            $appointment = Appointment::query()
                ->with('service')
                ->whereKey($data['appointment_id'])
                ->firstOrFail();

            return BillingInvoice::query()->create([
                'invoice_id' => $data['invoice_id'],
                'appointment_id' => $appointment->appointment_id,
                'total_bill' => $this->formatAmount($appointment->service->price),
                'payment_status' => $data['payment_status'],
                'paid_at' => $this->paidAt($data['payment_status'], $data['paid_at'] ?? null),
            ]);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateInvoice(string $id, array $data): BillingInvoice
    {
        $data = $this->sanitizeData($data);

        return DB::transaction(function () use ($id, $data): BillingInvoice {
            $invoice = BillingInvoice::query()->whereKey($id)->firstOrFail();
            $appointmentId = $data['appointment_id'] ?? $invoice->appointment_id;
            $appointment = Appointment::query()
                ->with('service')
                ->whereKey($appointmentId)
                ->firstOrFail();

            $invoice->update([
                'appointment_id' => $appointment->appointment_id,
                'total_bill' => $this->formatAmount($appointment->service->price),
                'payment_status' => $data['payment_status'],
                'paid_at' => $this->paidAt(
                    $data['payment_status'],
                    $data['paid_at'] ?? $invoice->paid_at,
                ),
            ]);

            return $invoice;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function sanitizeData(array $data): array
    {
        foreach ($data as $key => $value) {
            if (! is_string($value)) {
                continue;
            }

            $value = trim(strip_tags($value));

            if (str_ends_with($key, '_phone')) {
                $value = preg_replace('/\D+/', '', $value) ?? '';
            }

            $data[$key] = $value;
        }

        return $data;
    }

    private function formatAmount(string|int|float $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }

    private function paidAt(string $paymentStatus, mixed $paidAt): mixed
    {
        if ($paymentStatus === 'Unpaid') {
            return null;
        }

        return $paidAt ?? now();
    }
}
