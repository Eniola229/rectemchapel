<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Time;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
        public function index()
        {
            $today = now()->toDateString();
            $service = Time::where('day', now()->format('l'))->first();
            $students = Student::all();

            $present = Attendance::where('date', $today)
                ->where('service', $service->service ?? null)
                ->pluck('student_id')
                ->toArray();

            return view('admin.attendance', [
                'students' => $students,
                'time' => $service,
                'presentIds' => $present,
            ]);
        }

    public function markAttendance(Request $request)
    {
        try {
            $request->validate([
                'fingerprint' => 'required',
                'service' => 'required',
                'service_time' => 'required|date',
            ]);

            $student = Student::where('fingerprint', $request->fingerprint)->first();
            if (!$student) {
                return response()->json(['error' => 'Fingerprint not recognized'], 404);
            }

            $today = now()->toDateString();

            $existing = Attendance::where('student_id', $student->id)
                ->where('service', $request->service)
                ->where('date', $today)
                ->first();

            if ($existing) {
                return response()->json(['error' => 'Already marked today']);
            }

            $isLate = now()->gt(Carbon::parse($request->service_time));

            $attendance = Attendance::create([
                'id' => Str::uuid(),
                'student_id' => $student->id,
                'service' => $request->service,
                'date' => $today,
                'is_late' => $isLate,
            ]);

            return response()->json([
                'student' => $student,
                'is_late' => $isLate
            ]);
        } catch (\Throwable $e) {
            Log::error('Attendance Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }
}
