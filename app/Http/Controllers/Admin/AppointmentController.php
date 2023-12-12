<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $validated = request()->validate([
            'title' => ['required'],
            'client_id' => ['required',Rule::exists('clients','id')],
            'description' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required']
        ]);

        Appointment::create([
           'title' => $validated['title'],
           'description' => $validated['description'],
           'client_id' => $validated['client_id'],
           'start_time' => $validated['start_time'],
           'end_time' => $validated['end_time'],
            'status' => AppointmentStatus::SCHEDULED
        ]);

        return response()->json(['message' => 'success']);
    }

    public function edit(Appointment $appointment)
    {
        return $appointment;
    }

    public function update(Appointment $appointment)
    {

        $validated = request()->validate([
            'title' => ['required'],
            'client_id' => ['required',Rule::exists('clients','id')],
            'description' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required']
        ]);

        $appointment->update($validated);

        return response()->json(['message' => 'updated']);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json(['message' => 'deleted']);
    }
}
