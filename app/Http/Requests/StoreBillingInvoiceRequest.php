<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBillingInvoiceRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $values = [];

        foreach (['invoice_id', 'appointment_id', 'payment_status', 'paid_at'] as $key) {
            $value = $this->input($key);

            if (is_string($value)) {
                $values[$key] = trim(strip_tags($value));
            }
        }

        $this->merge($values);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'string', 'max:20', 'unique:billing_invoices,invoice_id'],
            'appointment_id' => ['required', 'string', 'exists:appointments,appointment_id', 'unique:billing_invoices,appointment_id'],
            'payment_status' => ['required', Rule::in(['Paid', 'Unpaid'])],
            'paid_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_id.unique' => 'This invoice ID is already in use.',
            'appointment_id.exists' => 'Select an existing appointment.',
            'appointment_id.unique' => 'This appointment already has an invoice.',
            'payment_status.in' => 'Choose Paid or Unpaid.',
            'paid_at.date' => 'Enter a valid payment date and time.',
        ];
    }
}
