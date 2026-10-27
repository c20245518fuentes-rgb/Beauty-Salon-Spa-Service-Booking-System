<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge($this->sanitizedStrings());
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_id' => ['required', 'string', 'max:20', 'unique:appointments,appointment_id'],
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
            'appointment_id.unique' => 'This appointment ID is already in use.',
            'client_id.exists' => 'Select an existing client.',
            'stylist_id.exists' => 'Select an existing stylist.',
            'service_id.exists' => 'Select an existing service.',
            'appointment_datetime.date' => 'Enter a valid appointment date and time.',
            'appointment_status.in' => 'Choose Pending, Completed, or Cancelled.',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function sanitizedStrings(): array
    {
        $values = [];

        foreach (['appointment_id', 'client_id', 'stylist_id', 'service_id', 'appointment_datetime', 'appointment_status'] as $key) {
            $value = $this->input($key);

            if (is_string($value)) {
                $values[$key] = trim(strip_tags($value));
            }
        }

        return $values;
    }
}
