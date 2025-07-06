<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Material;

class LibraryController extends Controller
{
    public function uploadMaterial(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'name'       => 'required|string',
            'course'     => 'required|string',
            'department' => 'required|string',
            'title'      => 'required|string',
            'year'       => 'required|string',
            'file'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'  // Allow images and PDF
        ]);

        try {
            $file = $request->file('file');
            $fileType = $file->getClientOriginalExtension();

            // Check if the file is an image
            if (in_array($fileType, ['jpg', 'jpeg', 'png'])) {
                // Upload image to Cloudinary
                $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'elibrary/images',
                ])->getSecurePath();
            }
            // Check if the file is a PDF
            elseif ($fileType === 'pdf') {
                // Upload PDF to Cloudinary
                $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'elibrary/pdfs',
                ])->getSecurePath();
            } else {
                // Return error if the file is neither image nor pdf
                return response()->json([
                    'errors' => ['file' => 'Invalid file type. Only images and PDFs are allowed.']
                ], 422);
            }

            // Create material using array format
            $material = Material::create([
                'name'       => $validated['name'],
                'course'     => $validated['course'],
                'department' => $validated['department'],
                'title'      => $validated['title'],
                'year'       => $validated['year'],
                'file_url'   => $uploadedFileUrl,  // Save the file URL
            ]);

            return response()->json([
                'message'  => 'Material uploaded successfully!',
                'material' => $material
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'errors' => ['file' => $e->getMessage()]
            ], 422);
        }
    }

    
    // Endpoint to get materials
    public function getMaterials()
    {
        $materials = Material::orderBy('created_at', 'desc')->get();
        return response()->json($materials);
    }
}
