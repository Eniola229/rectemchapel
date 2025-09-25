<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\Admin;
use Hash;
use Illuminate\View\View;
use App\Models\Student;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Student::orderBy('created_at', 'desc')->get();
        return view('admin.students', compact('students'));
    }  

public function store(Request $request)
{
    $request->validate([
        'name'       => 'required|string|max:255',
        'passport'   => 'required|string', // Base64 image string
        'fingerprint'=> 'required|string', // Base64 fingerprint image
        'matric_no'  => 'required|string|max:50|unique:students,matric_no',
        'email'      => 'required|email|unique:students,email',
        'department' => 'required|string|max:255',
        'school'     => 'required|string|max:255',
        'password'   => 'required|string|min:6|confirmed',
    ]);

    // --- Upload Passport to Cloudinary ---
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->passport));
    $tempFilePath = tempnam(sys_get_temp_dir(), 'passport_') . '.jpg';
    file_put_contents($tempFilePath, $imageData);

    $uploadedFile = Cloudinary::upload($tempFilePath, ['folder' => 'passport']);
    $uploadedFileUrl = $uploadedFile->getSecurePath();
    $publicId = $uploadedFile->getPublicId();

    // --- Enroll Fingerprint via SourceAFIS (.NET API) ---
// --- Enroll Fingerprint via SourceAFIS (.NET API) ---
    try {
        $response = Http::post('http://localhost:5140/api/fingerprint/enroll', [
            'FingerprintImage' => $request->fingerprint
        ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Failed to process fingerprint enrollment.',
                'details' => $response->body()
            ], 500);
        }

        $fingerprintTemplate = $response->json('fingerprint'); // ✅ real extracted template

        if (!$fingerprintTemplate) {
            return response()->json(['error' => 'No fingerprint data returned.'], 500);
        }
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Fingerprint API unavailable',
            'message' => $e->getMessage()
        ], 500);
    }


    // --- Store Student in Database ---
    $user = Student::create([
        'name'       => $request->name,
        'passport'   => $uploadedFileUrl,
        'passport_id'=> $publicId,
        'matric_no'  => $request->matric_no,
        'email'      => $request->email,
        'department' => $request->department,
        'school'     => $request->school,
        'password'   => Hash::make($request->password),
        'fingerprint'=> $fingerprintTemplate, // ✅ store SourceAFIS fingerprint string
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Student registered successfully!',
        'student' => $user
    ], 200);
}

public function show($id)
{
    $student = Student::find($id); // Retrieve the student by ID
    if (!$student) {
        return redirect()->back()->with('error', 'Student not found');
    }

    return view('admin.view-student', compact('student')); // Return the view with the student data
}


public function destroy(Request $request, $id)
{
    // Check if the authenticated user is 'super'
    if (Auth::user()->role !== 'SUPER') {
        return response()->json(['success' => false, 'message' => 'You do not have permission to delete this student.'], 403);
    }

    // Check if the passkey matches
    if ($request->passkey !== '20250502') {
        return response()->json(['success' => false, 'message' => 'Invalid passkey.'], 403);
    }

    // Find the student by ID
    $student = Student::find($id);

    if ($student) {
        // Delete the passport image from Cloudinary using the passport_id
        if ($student->passport_id) {
            try {
                // Delete the image from Cloudinary
                Cloudinary::destroy($student->passport_id);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Failed to delete passport image from Cloudinary.'], 500);
            }
        }

        // Now delete the student record from the database
        $student->delete();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
}
}
