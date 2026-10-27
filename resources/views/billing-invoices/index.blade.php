@extends('layouts.app')

@section('title', 'Billing invoices')

@section('content')
    <section class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-semibold tracking-tight">Billing invoices</h1>
            <p class="text-sm text-muted-foreground">Review invoices and keep payment statuses up to date.</p>
        </div>
        <a href="{{ route('billing-invoices.create') }}" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
            Create invoice
        </a>
    </section>

    <section class="overflow-hidden rounded-lg border bg-card shadow-sm">
        <div class="border-b px-6 py-4">
            <h2 class="font-medium">All invoices</h2>
            <p class="mt-1 text-sm text-muted-foreground">A record of appointment charges and payments.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="text-left">
                        <th class="h-11 px-4 font-medium text-muted-foreground">Invoice</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Appointment</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Client</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Service</th>
                        <th class="h-11 px-4 text-right font-medium text-muted-foreground">Total bill</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Payment</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Paid at</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Update payment</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse ($invoices as $invoice)
                        <tr class="border-b transition-colors hover:bg-muted/50">
                            <td class="whitespace-nowrap px-4 py-3 font-medium">{{ $invoice->invoice_id }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $invoice->appointment_id }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $invoice->appointment->client->client_name }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $invoice->appointment->service->service_name }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right font-medium">₱{{ $invoice->total_bill }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                    'bg-emerald-100 text-emerald-800' => $invoice->payment_status === 'Paid',
                                    'bg-secondary text-secondary-foreground' => $invoice->payment_status === 'Unpaid',
                                ])>
                                    {{ $invoice->payment_status }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $invoice->paid_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <form class="flex items-center gap-2" method="POST" action="{{ route('billing-invoices.update', $invoice->invoice_id) }}">
                                    @csrf
                                    @method('PUT')
                                    <select class="h-9 rounded-md border border-input bg-background px-3 text-sm shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" name="payment_status" aria-label="Payment status">
                                        @foreach (['Paid', 'Unpaid'] as $status)
                                            <option value="{{ $status }}" @selected($invoice->payment_status === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <button class="inline-flex h-9 items-center justify-center rounded-md border bg-background px-3 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground" type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-muted-foreground">
                                <p class="font-medium text-foreground">No invoices yet</p>
                                <p class="mt-1">Create an invoice from an appointment to see it here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($invoices->hasPages())
            <div class="border-t px-6 py-4">
                {{ $invoices->links() }}
            </div>
        @endif
    </section>
@endsection
