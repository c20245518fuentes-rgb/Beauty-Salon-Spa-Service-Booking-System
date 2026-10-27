<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBillingInvoiceRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $values = [];

        foreach (['appointment_id', 'payment_status', 'paid_at'] as $key) {
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
            'appointment_id' => [
                'sometimes',
                'required',
                'string',
                'exists:appointments,appointment_id',
                Rule::unique('billing_invoices', 'appointment_id')->ignore($this->route('billing_invoice'), 'invoice_id'),
            ],
            'payment_status' => ['required', Rule::in(['Paid', 'Unpaid'])],
            'paid_at' => ['sometimes', 'nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'appointment_id.exists' => 'Select an existing appointment.',
            'appointment_id.unique' => 'This appointment already has an invoice.',
            'payment_status.in' => 'Choose Paid or Unpaid.',
            'paid_at.date' => 'Enter a valid payment date and time.',
        ];
    }
}
