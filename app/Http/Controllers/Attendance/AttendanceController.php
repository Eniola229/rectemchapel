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
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    public function index()
    {

        $today = now()->format('l'); // e.g. 'Sunday', 'Monday', etc.
        $service = Time::where('day', $today)->first();
        $now = now()->subHours(8);
        
        $attendanceClosed = false;

        if (!$service) {
            return view('admin.attendance', [
                'students' => [],
                'presentIds' => [],
                'time' => null,
                'attendanceClosed' => true
            ]);
        }

        $students = Student::all();
        $present = Attendance::whereDate('created_at', now())
            ->where('service', $service->service)
            ->pluck('student_id')
            ->toArray();

        return view('admin.attendance', [
            'students' => $students,
            'presentIds' => $present,
            'time' => $service,
            'attendanceClosed' => $now->format('H:i') > $service->time
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

public function attendancehistory(Request $request)
{
    $query = Attendance::with('student')->orderByDesc('created_at');

    if ($request->filled('year')) {
        $query->whereYear('created_at', $request->year);
    }
    if ($request->filled('month')) {
        $query->whereMonth('created_at', $request->month);
    }
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('student', function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('matric_no', 'like', "%$search%");
        });
    }

    // Get all results first
    $all = $query->get();

    // Group by service + date
    $grouped = $all->groupBy(function ($record) {
        return $record->service . '|' . $record->created_at->toDateString();
    });

    // Now paginate the groups (not individual rows)
    $perPage = 10;
    $page = request('page', 1);
    $paged = $grouped->forPage($page, $perPage);

    $attendances = new \Illuminate\Pagination\LengthAwarePaginator(
        $paged,
        $grouped->count(),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('admin.attendance-history', compact('attendances'));
}

    public function exportCsv(Request $request)
{
    $date = $request->query('date');
    $service = $request->query('service');

    $records = Attendance::with('student')
        ->whereDate('created_at', $date)
        ->where('service', $service)
        ->get();

    $filename = "attendance_{$service}_{$date}.csv";

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($records) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Name', 'Matric No', 'Service', 'Status', 'Time']);

        foreach ($records as $record) {
            fputcsv($handle, [
                $record->student->name,
                $record->student->matric_no,
                $record->service,
                $record->is_late ? 'Late' : 'On Time',
                $record->created_at->format('h:i A'),
            ]);
        }

        fclose($handle);
    };

    return Response::stream($callback, 200, $headers);
}


public function exportPdf(Request $request)
{
    $date = $request->query('date');
    $service = $request->query('service');

    $records = Attendance::with('student')
        ->whereDate('created_at', $date)
        ->where('service', $service)
        ->get();

    $pdf = Pdf::loadView('exports.attendance-pdf', [
        'records' => $records,
        'service' => $service,
        'date' => $date,
    ]);

    return $pdf->download("attendance_{$service}_{$date}.pdf");
}
}
