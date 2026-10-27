@extends('layouts.app')

@section('title', 'Create appointment')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div class="space-y-1">
            <a href="{{ route('appointments.index') }}" class="text-sm text-muted-foreground hover:text-foreground">← Back to appointments</a>
            <h1 class="pt-2 text-2xl font-semibold tracking-tight">Create appointment</h1>
            <p class="text-sm text-muted-foreground">Add a new booking to your salon schedule.</p>
        </div>

        <form method="POST" action="{{ route('appointments.store') }}" class="space-y-6 rounded-lg border bg-card p-6 shadow-sm">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <label class="grid gap-2 text-sm font-medium">
                    Appointment ID
                    <input class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-normal shadow-sm outline-none transition placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring" name="appointment_id" value="{{ old('appointment_id') }}" required maxlength="20">
                </label>
                <label class="grid gap-2 text-sm font-medium">
                    Client
                    <select class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-normal shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" name="client_id" required>
                        @foreach ($clients as $client)
                            <option value="{{ $client->client_id }}" @selected(old('client_id') === $client->client_id)>
                                {{ $client->client_name }} ({{ $client->client_id }})
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium">
                    Stylist
                    <select class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-normal shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" name="stylist_id" required>
                        @foreach ($stylists as $stylist)
                            <option value="{{ $stylist->stylist_id }}" @selected(old('stylist_id') === $stylist->stylist_id)>
                                {{ $stylist->stylist_name }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium">
                    Service
                    <select class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-normal shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" name="service_id" required>
                        @foreach ($services as $service)
                            <option value="{{ $service->service_id }}" @selected(old('service_id') === $service->service_id)>
                                {{ $service->service_name }} — ₱{{ $service->price }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium">
                    Date and time
                    <input class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-normal shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" type="datetime-local" name="appointment_datetime" value="{{ old('appointment_datetime') }}" required>
                </label>
                <label class="grid gap-2 text-sm font-medium">
                    Status
                    <select class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-normal shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" name="appointment_status" required>
                        @foreach (['Pending', 'Completed', 'Cancelled'] as $status)
                            <option value="{{ $status }}" @selected(old('appointment_status', 'Pending') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div class="flex flex-col-reverse gap-3 border-t pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('appointments.index') }}" class="inline-flex h-10 items-center justify-center rounded-md border bg-background px-4 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground">Cancel</a>
                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">Save appointment</button>
            </div>
        </form>
    </div>
@endsection
