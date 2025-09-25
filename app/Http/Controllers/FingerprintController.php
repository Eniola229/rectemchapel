<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class FingerprintController extends Controller
{
public function capture()
{
    set_time_limit(300);

    $pythonPath = 'C:\Users\Admin\AppData\Local\Programs\Python\Python313-32\python.exe'; 
    $scriptPath = base_path('fingerprint/fingerprint_capture.py');
    $outputPath = base_path('fingerprint/fingerprint_output.json');

    // Clean previous output
    if (file_exists($outputPath)) {
        @unlink($outputPath);
    }

    // Run Python synchronously
    exec("\"{$pythonPath}\" \"{$scriptPath}\"", $output, $return_var);

    if ($return_var !== 0 || !file_exists($outputPath)) {
        return response()->json([
            'success' => false,
            'message' => 'Fingerprint capture failed'
        ]);
    }

    $result = json_decode(file_get_contents($outputPath), true);
    return response()->json($result);
}



}
