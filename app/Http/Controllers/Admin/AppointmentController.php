<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index() {

        return Appointment::query()
            ->with('client:id,first_name,last_name')
            ->when(\request('status'), function ($query) {
                return $query->where('status', AppointmentStatus::from(\request('status')));
            })
            ->latest()
            ->paginate(10)
            ->through(fn ($appointment) => [
                'id' => $appointment->id,
                'client' => $appointment->client,
                'start_time' => $appointment->start_time->format('Y-m-d h:i A'),
                'end_time' => $appointment->end_time->format('Y-m-d h:i A'),
                'status' => [
                    'name' => $appointment->status->name,
                    'color' => $appointment->status->color(),
                ]
            ]);
    }

    public function store()
    {
        Appointment::create([
           'title' => \request('title'),
           'description' => \request('description'),
           'client_id' => 1,
           'start_time' => now(),
           'end_time' => now(),
            'status' => AppointmentStatus::SCHEDULED
        ]);

        return response()->json(['message' => 'success']);
    }
}
