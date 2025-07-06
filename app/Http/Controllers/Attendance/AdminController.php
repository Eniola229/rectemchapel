<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cloudinary;
use Validator;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        $admins = Admin::all();
        return view('admin.admins', compact('admins'));
    }
    // Store Admin (POST Request)
    public function store(Request $request)
    {
        // Check if the authenticated user is 'SUPER'
        if (Auth::user()->role !== 'SUPER') {
            return response()->json(['success' => false, 'message' => 'You do not have permission to create an admin.'], 403);
        }

        // Validate form inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'passport' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        // Upload Passport to Cloudinary
        $passportData = $request->input('passport');
        $passportPath = null;
        try {
            // Upload passport image to Cloudinary
            $upload = Cloudinary::upload($passportData, [
                'folder' => 'passports',
            ]);
            $passportPath = $upload->getSecurePath();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error uploading passport image.'], 500);
        }

        // Create New User (Admin/Student)
        $user = new Admin();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->password = bcrypt($request->input('password'));
        $user->avatar = $passportPath; // Store the Cloudinary URL
        $user->save();

        return response()->json(['success' => true, 'message' => 'Admin created successfully']);
    }

    // Delete Admin (DELETE Request)
    public function destroy(Request $request, $id)
    {
        // Check if the authenticated user is 'SUPER'
        if (Auth::user()->role !== 'SUPER') {
            return response()->json(['success' => false, 'message' => 'You do not have permission to delete an admin.'], 403);
        }

        // Check passkey for deletion
        if ($request->input('passkey') !== '20250502') {
            return response()->json(['success' => false, 'message' => 'Invalid passkey.'], 403);
        }

        $user = Admin::find($id);

        if ($user) {
            // Delete the passport image from Cloudinary
            if ($user->passport) {
                try {
                    Cloudinary::destroy($user->passport); // Destroy the passport image from Cloudinary
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Failed to delete passport image.'], 500);
                }
            }

            // Delete the user
            $user->delete();
            return response()->json(['success' => true, 'message' => 'Admin deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }
}
