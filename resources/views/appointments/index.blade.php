@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
    <section class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-semibold tracking-tight">Appointments</h1>
            <p class="text-sm text-muted-foreground">Manage your salon schedule and client bookings.</p>
        </div>
        <a href="{{ route('appointments.create') }}" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
            Create appointment
        </a>
    </section>

    <section class="overflow-hidden rounded-lg border bg-card shadow-sm">
        <div class="border-b px-6 py-4">
            <h2 class="font-medium">All appointments</h2>
            <p class="mt-1 text-sm text-muted-foreground">A list of appointments scheduled at your salon.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="text-left">
                        <th class="h-11 px-4 font-medium text-muted-foreground">ID</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Client</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Stylist</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Service</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Date and time</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Status</th>
                        <th class="h-11 px-4 font-medium text-muted-foreground">Invoice</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse ($appointments as $appointment)
                        <tr class="border-b transition-colors hover:bg-muted/50">
                            <td class="whitespace-nowrap px-4 py-3 font-medium">{{ $appointment->appointment_id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $appointment->client->client_name }}</div>
                                <div class="text-xs text-muted-foreground">{{ $appointment->client->client_phone }}</div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $appointment->stylist->stylist_name }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <div>{{ $appointment->service->service_name }}</div>
                                <div class="text-xs text-muted-foreground">₱{{ $appointment->service->price }}</div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $appointment->appointment_datetime->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                    'bg-emerald-100 text-emerald-800' => $appointment->appointment_status === 'Completed',
                                    'bg-destructive/10 text-destructive' => $appointment->appointment_status === 'Cancelled',
                                    'bg-secondary text-secondary-foreground' => $appointment->appointment_status === 'Pending',
                                ])>
                                    {{ $appointment->appointment_status }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                @if ($appointment->invoice)
                                    {{ $appointment->invoice->invoice_id }} — ₱{{ $appointment->invoice->total_bill }}
                                @else
                                    <a class="font-medium underline underline-offset-4 hover:text-muted-foreground" href="{{ route('billing-invoices.create') }}">Create invoice</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-muted-foreground">
                                <p class="font-medium text-foreground">No appointments yet</p>
                                <p class="mt-1">Create an appointment to get your schedule started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($appointments->hasPages())
            <div class="border-t px-6 py-4">
                {{ $appointments->links() }}
            </div>
        @endif
    </section>
@endsection
