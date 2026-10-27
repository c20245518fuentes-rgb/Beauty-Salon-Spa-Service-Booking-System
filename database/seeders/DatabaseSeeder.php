<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\BillingInvoice;
use App\Models\Client;
use App\Models\Service;
use App\Models\Stylist;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Client::query()->updateOrCreate(
            ['client_id' => 'C001'],
            ['client_name' => 'Maria Santos', 'client_phone' => '09171234567'],
        );
        Client::query()->updateOrCreate(
            ['client_id' => 'C002'],
            ['client_name' => 'Juan Dela Cruz', 'client_phone' => '09181234567'],
        );

        Stylist::query()->updateOrCreate(
            ['stylist_id' => 'S001'],
            ['stylist_name' => 'Anna Cruz'],
        );
        Stylist::query()->updateOrCreate(
            ['stylist_id' => 'S002'],
            ['stylist_name' => 'Bea Reyes'],
        );

        Service::query()->updateOrCreate(
            ['service_id' => 'SV001'],
            ['service_name' => 'Haircut', 'service_category' => 'Hair', 'service_duration_min' => 45, 'price' => '500.00'],
        );
        Service::query()->updateOrCreate(
            ['service_id' => 'SV002'],
            ['service_name' => 'Manicure', 'service_category' => 'Nail', 'service_duration_min' => 30, 'price' => '300.00'],
        );

        Appointment::query()->updateOrCreate(
            ['appointment_id' => 'A001'],
            [
                'client_id' => 'C001',
                'stylist_id' => 'S001',
                'service_id' => 'SV001',
                'appointment_datetime' => '2024-06-01 10:00:00',
                'appointment_status' => 'Completed',
            ],
        );
        Appointment::query()->updateOrCreate(
            ['appointment_id' => 'A002'],
            [
                'client_id' => 'C001',
                'stylist_id' => 'S001',
                'service_id' => 'SV002',
                'appointment_datetime' => '2024-06-01 11:00:00',
                'appointment_status' => 'Pending',
            ],
        );

        BillingInvoice::query()->updateOrCreate(
            ['invoice_id' => 'INV001'],
            [
                'appointment_id' => 'A001',
                'total_bill' => '500.00',
                'payment_status' => 'Paid',
                'paid_at' => '2024-06-01 11:15:00',
            ],
        );
        BillingInvoice::query()->updateOrCreate(
            ['invoice_id' => 'INV002'],
            [
                'appointment_id' => 'A002',
                'total_bill' => '300.00',
                'payment_status' => 'Unpaid',
                'paid_at' => null,
            ],
        );
    }
}
