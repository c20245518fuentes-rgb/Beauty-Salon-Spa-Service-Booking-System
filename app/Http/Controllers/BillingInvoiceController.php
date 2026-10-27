<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillingInvoiceRequest;
use App\Http\Requests\UpdateBillingInvoiceRequest;
use App\Models\Appointment;
use App\Models\BillingInvoice;
use App\Services\SalonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BillingInvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = BillingInvoice::query()
            ->with(['appointment.client', 'appointment.stylist', 'appointment.service'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('billing-invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $appointments = Appointment::query()
            ->with(['client', 'service'])
            ->whereDoesntHave('invoice')
            ->orderByDesc('appointment_datetime')
            ->get();

        return view('billing-invoices.create', compact('appointments'));
    }

    public function store(StoreBillingInvoiceRequest $request, SalonService $salonService): RedirectResponse
    {
        $salonService->createInvoice($request->validated());

        return redirect()->route('billing-invoices.index')->with('status', 'Invoice created.');
    }

    public function update(UpdateBillingInvoiceRequest $request, SalonService $salonService, string $billing_invoice): RedirectResponse
    {
        $salonService->updateInvoice($billing_invoice, $request->validated());

        return redirect()->route('billing-invoices.index')->with('status', 'Invoice updated.');
    }
}
