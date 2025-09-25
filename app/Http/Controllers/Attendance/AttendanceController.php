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
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Http;

use App\Helpers\FingerprintHelper;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = now()->format('l'); // e.g. 'Sunday', 'Monday', etc.
        $service = Time::where('day', $today)->first();
        $now = now();
        
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
        // Fix service_time if only time is sent (add today's date)
        if ($request->has('service_time') && !preg_match('/\d{4}-\d{2}-\d{2}/', $request->service_time)) {
            $request->merge([
                'service_time' => now()->toDateString() . ' ' . $request->service_time
            ]);
        }

        $request->validate([
            'fingerprint'  => 'required|string', // base64 string (from device)
            'service'      => 'required|string',
            'service_time' => 'required|date',
        ]);

        $capturedBase64 = $request->fingerprint;
        $today = now()->toDateString();
        $matchedStudent = null;

        // Step 1: Convert captured image into a SourceAFIS template
        $enrollResponse = Http::post('http://localhost:5140/api/fingerprint/enroll', [
            'FingerprintImage' => $capturedBase64
        ]);

        if (!$enrollResponse->successful()) {
            return response()->json([
                'error'   => 'Failed to enroll captured fingerprint',
                'details' => $enrollResponse->body()
            ], 500);
        }

        $probeTemplate = $enrollResponse->json('fingerprint');
        if (!$probeTemplate) {
            return response()->json(['error' => 'No template returned from API'], 500);
        }

        // Step 2: Compare captured template with each student's stored template
        foreach (Student::all() as $student) {
            if (empty($student->fingerprint)) {
                continue;
            }

            $matchResponse = Http::post('http://localhost:5140/api/fingerprint/match', [
                'ProbeTemplate'     => $probeTemplate,
                'CandidateTemplate' => $student->fingerprint,
            ]);

            if ($matchResponse->successful()) {
                $isMatch = $matchResponse->json('isMatch');
                if ($isMatch) {
                    $matchedStudent = $student;
                    break;
                }
            }
        }

        if (!$matchedStudent) {
            return response()->json(['error' => 'User Does Not Exist or Fingerprint Mismatch'], 422);
        }

        // Step 3: Prevent duplicate attendance for today
        $existing = Attendance::where('student_id', $matchedStudent->id)
            ->where('service', $request->service)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Already marked today'], 422);
        }

        // Step 4: Mark attendance
        $isLate = now()->gt(Carbon::parse($request->service_time));

        Attendance::create([
            'id'         => Str::uuid(),
            'student_id' => $matchedStudent->id,
            'service'    => $request->service,
            'date'       => $today,
            'is_late'    => $isLate,
        ]);

        return response()->json([
            'student' => $matchedStudent,
            'is_late' => $isLate
        ]);

    } catch (\Throwable $e) {
        \Log::error('Attendance Error: ' . $e->getMessage());
        return response()->json(['error' => 'Server Error'], 500);
    }
}

public function checkout(Request $request)
{
    // Fix service_time if only time is sent (add today's date)
        if ($request->has('service_time') && !preg_match('/\d{4}-\d{2}-\d{2}/', $request->service_time)) {
            $request->merge([
                'service_time' => now()->toDateString() . ' ' . $request->service_time
            ]);
        }
    try {
        $request->validate([
            'fingerprint'  => 'required|string',
            'service'      => 'required|string',
            'service_time' => 'required|date',
        ]);

        $capturedBase64 = $request->fingerprint;
        $today = now()->toDateString();
        $matchedStudent = null;

        // 1️⃣ Convert captured image into template
        $enrollResponse = Http::post('http://localhost:5140/api/fingerprint/enroll', [
            'FingerprintImage' => $capturedBase64
        ]);

        if (!$enrollResponse->successful()) {
            return response()->json([
                'error' => 'Failed to enroll captured fingerprint'
            ], 500);
        }

        $probeTemplate = $enrollResponse->json('fingerprint');

        // 2️⃣ Compare with stored student templates
        foreach (Student::all() as $student) {
            if (empty($student->fingerprint)) continue;

            $matchResponse = Http::post('http://localhost:5140/api/fingerprint/match', [
                'ProbeTemplate'     => $probeTemplate,
                'CandidateTemplate' => $student->fingerprint,
            ]);

            if ($matchResponse->successful() && $matchResponse->json('isMatch')) {
                $matchedStudent = $student;
                break;
            }
        }

        if (!$matchedStudent) {
            return response()->json(['error' => 'User does not exist or fingerprint mismatch'], 422);
        }

        // 3️⃣ Find today's attendance for this student
        $attendance = Attendance::where('student_id', $matchedStudent->id)
            ->where('service', $request->service)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['error' => 'Attendance not marked yet'], 422);
        }

        if ($attendance->checked_out_at) {
            return response()->json(['error' => 'Already checked out today'], 422);
        }

        // 4️⃣ Mark checkout
        $attendance->update(['checked_out_at' => now()]);

        return response()->json(['student' => $matchedStudent]);

    } catch (\Throwable $e) {
        \Log::error('Checkout Error: ' . $e->getMessage());
        return response()->json(['error' => 'Server Error'], 500);
    }
}

public function markMatric(Request $request)
{
        // Fix service_time if only time is sent (add today's date)
        if ($request->has('service_time') && !preg_match('/\d{4}-\d{2}-\d{2}/', $request->service_time)) {
            $request->merge([
                'service_time' => now()->toDateString() . ' ' . $request->service_time
            ]);
        }
    $validator = Validator::make($request->all(), [
        'matric_number' => 'required|string|exists:students,matric_no',
        'service'       => 'required|string',
        'service_time'  => 'required|date',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors()->first()
        ], 422);
    }

    try {
        $student = Student::where('matric_no', $request->matric_number)->first();

        $attendance = Attendance::firstOrCreate([
            'student_id' => $student->id,
            'service'    => $request->service,
            'date'       => now()->toDateString(),
        ], [
            'is_late' => now()->gt($request->service_time)
        ]);

        if ($attendance->wasRecentlyCreated) {
            return response()->json(['student' => $student]);
        } else {
            return response()->json(['error' => 'Attendance already marked for today'], 422);
        }
    } catch (\Throwable $e) {
        \Log::error('Mark Matric Error: ' . $e->getMessage());
        return response()->json(['error' => 'Server error'], 500);
    }
}

public function checkoutMatric(Request $request)
{
        // Fix service_time if only time is sent (add today's date)
        if ($request->has('service_time') && !preg_match('/\d{4}-\d{2}-\d{2}/', $request->service_time)) {
            $request->merge([
                'service_time' => now()->toDateString() . ' ' . $request->service_time
            ]);
        }
    $validator = Validator::make($request->all(), [
        'matric_number' => 'required|string|exists:students,matric_no',
        'service'       => 'required|string',
        'service_time'  => 'required|date',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors()->first()
        ], 422);
    }

    try {
        $student = Student::where('matric_no', $request->matric_number)->first();

        $attendance = Attendance::where('student_id', $student->id)
            ->where('service', $request->service)
            ->whereDate('date', now()->toDateString())
            ->first();

        if (!$attendance) {
            return response()->json(['error' => 'Attendance not marked yet'], 422);
        }

        if ($attendance->checked_out_at) {
            return response()->json(['error' => 'Already checked out today'], 422);
        }

        $attendance->update(['checked_out_at' => now()]);

        return response()->json(['student' => $student]);
    } catch (\Throwable $e) {
        \Log::error('Checkout Matric Error: ' . $e->getMessage());
        return response()->json(['error' => 'Server error'], 500);
    }
}

/**
 * Compare two images using SSIM (grayscale + resize)
 */

private function imagesAreSimilar($image1, $image2, $threshold = 85)
{
    $manager = new ImageManager(new Driver());

    $img1 = $manager->read($image1)->resize(200, 200)->greyscale();
    $img2 = $manager->read($image2)->resize(200, 200)->greyscale();

    $pixels1 = $img1->encode()->toString();
    $pixels2 = $img2->encode()->toString();

    $diff = 0;
    $len = strlen($pixels1);
    for ($i = 0; $i < $len; $i++) {
        if ($pixels1[$i] !== $pixels2[$i]) $diff++;
    }

    $similarity = (1 - ($diff / $len)) * 100;

    return $similarity >= $threshold;
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
