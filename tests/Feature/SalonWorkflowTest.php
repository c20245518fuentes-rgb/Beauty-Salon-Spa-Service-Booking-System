<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\BillingInvoice;
use App\Models\Client;
use App\Models\Service;
use App\Models\Stylist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalonWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_can_be_created_and_listed_with_its_relationships(): void
    {
        $this->createReferenceRecords();

        $response = $this->post(route('appointments.store'), [
            'appointment_id' => ' A003 ',
            'client_id' => 'C001',
            'stylist_id' => 'S001',
            'service_id' => 'SV001',
            'appointment_datetime' => '2024-06-02T10:00',
            'appointment_status' => 'Pending',
        ]);

        $response->assertRedirect(route('appointments.index'));
        $this->assertDatabaseHas('appointments', [
            'appointment_id' => 'A003',
            'client_id' => 'C001',
            'appointment_status' => 'Pending',
        ]);

        $this->get(route('appointments.index'))
            ->assertOk()
            ->assertSee('A003')
            ->assertSee('Maria Santos')
            ->assertSee('Haircut');
    }

    public function test_invoice_total_comes_from_the_appointment_service_and_paid_at_is_managed(): void
    {
        $this->createAppointment();

        $response = $this->post(route('billing-invoices.store'), [
            'invoice_id' => 'INV003',
            'appointment_id' => 'A001',
            'total_bill' => '0.01',
            'payment_status' => 'Paid',
        ]);

        $response->assertRedirect(route('billing-invoices.index'));

        $invoice = BillingInvoice::query()->findOrFail('INV003');
        $this->assertSame('500.00', $invoice->total_bill);
        $this->assertNotNull($invoice->paid_at);

        $this->get(route('billing-invoices.index'))
            ->assertOk()
            ->assertSee('Maria Santos')
            ->assertSee('₱500.00');
    }

    public function test_changing_an_appointment_service_recalculates_its_invoice_total(): void
    {
        $this->createAppointment();
        $this->post(route('billing-invoices.store'), [
            'invoice_id' => 'INV001',
            'appointment_id' => 'A001',
            'payment_status' => 'Unpaid',
        ])->assertRedirect(route('billing-invoices.index'));

        $response = $this->put(route('appointments.update', 'A001'), [
            'client_id' => 'C001',
            'stylist_id' => 'S001',
            'service_id' => 'SV002',
            'appointment_datetime' => '2024-06-01T10:00',
            'appointment_status' => 'Completed',
        ]);

        $response->assertRedirect(route('appointments.index'));
        $this->assertSame('300.00', BillingInvoice::query()->findOrFail('INV001')->total_bill);
    }

    public function test_marking_an_invoice_unpaid_clears_its_paid_at_value(): void
    {
        $this->createAppointment();
        $this->post(route('billing-invoices.store'), [
            'invoice_id' => 'INV001',
            'appointment_id' => 'A001',
            'payment_status' => 'Paid',
            'paid_at' => '2024-06-01T11:15',
        ])->assertRedirect(route('billing-invoices.index'));

        $response = $this->put(route('billing-invoices.update', 'INV001'), [
            'payment_status' => 'Unpaid',
        ]);

        $response->assertRedirect(route('billing-invoices.index'));
        $invoice = BillingInvoice::query()->findOrFail('INV001');
        $this->assertSame('Unpaid', $invoice->payment_status);
        $this->assertNull($invoice->paid_at);
    }

    public function test_create_pages_provide_required_reference_data(): void
    {
        $this->createAppointment();

        $this->get(route('appointments.create'))
            ->assertOk()
            ->assertSee('Maria Santos')
            ->assertSee('Anna Cruz');

        $this->get(route('billing-invoices.create'))
            ->assertOk()
            ->assertSee('A001')
            ->assertSee('Haircut');
    }

    public function test_database_seeder_loads_the_provided_salon_dataset(): void
    {
        $this->seed();

        $this->assertDatabaseCount('clients', 2);
        $this->assertDatabaseCount('stylists', 2);
        $this->assertDatabaseCount('services', 2);
        $this->assertDatabaseCount('appointments', 2);
        $this->assertDatabaseCount('billing_invoices', 2);
        $this->assertSame('500.00', BillingInvoice::query()->findOrFail('INV001')->total_bill);
    }

    private function createReferenceRecords(): void
    {
        Client::query()->create([
            'client_id' => 'C001',
            'client_name' => 'Maria Santos',
            'client_phone' => '09171234567',
        ]);
        Stylist::query()->create([
            'stylist_id' => 'S001',
            'stylist_name' => 'Anna Cruz',
        ]);
        Service::query()->create([
            'service_id' => 'SV001',
            'service_name' => 'Haircut',
            'service_category' => 'Hair',
            'service_duration_min' => 45,
            'price' => '500.00',
        ]);
        Service::query()->create([
            'service_id' => 'SV002',
            'service_name' => 'Manicure',
            'service_category' => 'Nail',
            'service_duration_min' => 30,
            'price' => '300.00',
        ]);
    }

    private function createAppointment(): Appointment
    {
        $this->createReferenceRecords();

        return Appointment::query()->create([
            'appointment_id' => 'A001',
            'client_id' => 'C001',
            'stylist_id' => 'S001',
            'service_id' => 'SV001',
            'appointment_datetime' => '2024-06-01 10:00:00',
            'appointment_status' => 'Completed',
        ]);
    }
}
