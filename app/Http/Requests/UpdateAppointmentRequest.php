<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $values = [];

        foreach (['client_id', 'stylist_id', 'service_id', 'appointment_datetime', 'appointment_status'] as $key) {
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
            'client_id' => ['required', 'string', 'exists:clients,client_id'],
            'stylist_id' => ['required', 'string', 'exists:stylists,stylist_id'],
            'service_id' => ['required', 'string', 'exists:services,service_id'],
            'appointment_datetime' => ['required', 'date'],
            'appointment_status' => ['required', Rule::in(['Pending', 'Completed', 'Cancelled'])],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.exists' => 'Select an existing client.',
            'stylist_id.exists' => 'Select an existing stylist.',
            'service_id.exists' => 'Select an existing service.',
            'appointment_datetime.date' => 'Enter a valid appointment date and time.',
            'appointment_status.in' => 'Choose Pending, Completed, or Cancelled.',
        ];
    }
}
