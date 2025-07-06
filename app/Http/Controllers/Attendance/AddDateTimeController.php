<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;

class AddDateTimeController extends Controller
{
    public function index(Request $request)
    {
        $times = Time::all();
        return view('admin.time');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day' => 'required|string',
            'time' => 'required',
            'service' => 'required|string',
        ]);

        Time::create($validated);

        return response()->json(['message' => 'Schedule saved successfully.']);
    }

     public function all()
    {
        return Time::orderBy('day')->get();
    }

    public function edit($id)
    {
        return Time::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'day' => 'required|string',
            'time' => 'required',
            'service' => 'required|string',
        ]);

        $schedule = Time::findOrFail($id);
        $schedule->update($data);

        return response()->json(['message' => 'Updated']);
    }

    public function destroy($id)
    {
        Time::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
