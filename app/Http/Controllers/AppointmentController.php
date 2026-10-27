<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\Stylist;
use App\Services\SalonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $appointments = Appointment::query()
            ->with(['client', 'stylist', 'service', 'invoice'])
            ->orderByDesc('appointment_datetime')
            ->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    public function create(): View
    {
        return view('appointments.create', [
            'clients' => Client::query()->orderBy('client_name')->get(),
            'stylists' => Stylist::query()->orderBy('stylist_name')->get(),
            'services' => Service::query()->orderBy('service_name')->get(),
        ]);
    }

    public function store(StoreAppointmentRequest $request, SalonService $salonService): RedirectResponse
    {
        $salonService->createAppointment($request->validated());

        return redirect()->route('appointments.index')->with('status', 'Appointment created.');
    }

    public function update(UpdateAppointmentRequest $request, SalonService $salonService, string $appointment): RedirectResponse
    {
        $salonService->updateAppointment($appointment, $request->validated());

        return redirect()->route('appointments.index')->with('status', 'Appointment updated.');
    }
}
