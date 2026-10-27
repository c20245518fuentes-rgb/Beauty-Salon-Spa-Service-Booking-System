<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Salon Studio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-foreground">
    <header class="border-b bg-card">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-6 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('appointments.index') }}" class="flex shrink-0 items-center gap-3">
                <span class="flex size-9 items-center justify-center rounded-lg bg-primary text-sm font-semibold text-primary-foreground">S</span>
                <span class="text-sm font-semibold tracking-tight">Salon Studio</span>
            </a>

            <nav aria-label="Main navigation" class="flex items-center gap-1">
                <a
                    href="{{ route('appointments.index') }}"
                    @class([
                        'rounded-md px-3 py-2 text-sm font-medium transition-colors',
                        'bg-secondary text-secondary-foreground' => request()->routeIs('appointments.*'),
                        'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => ! request()->routeIs('appointments.*'),
                    ])
                >
                    Appointments
                </a>
                <a
                    href="{{ route('billing-invoices.index') }}"
                    @class([
                        'rounded-md px-3 py-2 text-sm font-medium transition-colors',
                        'bg-secondary text-secondary-foreground' => request()->routeIs('billing-invoices.*'),
                        'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => ! request()->routeIs('billing-invoices.*'),
                    ])
                >
                    Invoices
                </a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div role="status" class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="rounded-md border border-destructive/30 bg-destructive/5 px-4 py-3 text-sm text-destructive">
                <p class="font-medium">Please check the following:</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
