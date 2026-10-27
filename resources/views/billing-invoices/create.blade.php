@extends('layouts.app')

@section('title', 'Create invoice')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div class="space-y-1">
            <a href="{{ route('billing-invoices.index') }}" class="text-sm text-muted-foreground hover:text-foreground">← Back to invoices</a>
            <h1 class="pt-2 text-2xl font-semibold tracking-tight">Create invoice</h1>
            <p class="text-sm text-muted-foreground">Create a bill for a completed or scheduled appointment.</p>
        </div>

        <form method="POST" action="{{ route('billing-invoices.store') }}" class="space-y-6 rounded-lg border bg-card p-6 shadow-sm">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <label class="grid gap-2 text-sm font-medium">
                    Invoice ID
                    <input class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-normal shadow-sm outline-none transition placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring" name="invoice_id" value="{{ old('invoice_id') }}" required maxlength="20">
                </label>
                <label class="grid gap-2 text-sm font-medium">
                    Appointment
                    <select class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-normal shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" name="appointment_id" required>
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->appointment_id }}" @selected(old('appointment_id') === $appointment->appointment_id)>
                                {{ $appointment->appointment_id }} — {{ $appointment->client->client_name }} — {{ $appointment->service->service_name }} — ₱{{ $appointment->service->price }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium">
                    Payment status
                    <select class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-normal shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" name="payment_status" required>
                        @foreach (['Paid', 'Unpaid'] as $status)
                            <option value="{{ $status }}" @selected(old('payment_status', 'Unpaid') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium">
                    Paid at <span class="font-normal text-muted-foreground">(optional)</span>
                    <input class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-normal shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" type="datetime-local" name="paid_at" value="{{ old('paid_at') }}">
                </label>
            </div>
            <p class="rounded-md bg-muted px-4 py-3 text-sm text-muted-foreground">The invoice total is calculated from the appointment service price.</p>
            <div class="flex flex-col-reverse gap-3 border-t pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('billing-invoices.index') }}" class="inline-flex h-10 items-center justify-center rounded-md border bg-background px-4 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground">Cancel</a>
                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">Save invoice</button>
            </div>
        </form>
    </div>
@endsection
