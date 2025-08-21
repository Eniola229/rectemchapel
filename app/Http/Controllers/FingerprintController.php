<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class FingerprintController extends Controller
{
    public function capture()
    {
        // Path to your FutronicCapture.exe file
        $exePath = base_path('fingerprint/FutronicCapture.exe');

        // Working directory for the process
        $workingDir = public_path();

        // Directory where .NET runtime files will be extracted
        $dotnetCache = storage_path('dotnet_cache');

        // Ensure the directory exists and is writable
        if (!file_exists($dotnetCache)) {
            mkdir($dotnetCache, 0777, true);
        }

        // Create a new process to run the executable
        $process = new Process([$exePath]);
        $process->setWorkingDirectory($workingDir);
        $process->setEnv([
            'DOTNET_BUNDLE_EXTRACT_BASE_DIR' => $dotnetCache
        ]);
        $process->setTimeout(30); // Optional: timeout after 30 seconds

        try {
            // Run the process and throw exception if it fails
            $process->mustRun();

            // Get the output from the executable
            $output = $process->getOutput();

            return response()->json([
                'success' => true,
                'message' => 'Fingerprint captured successfully.',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            // Capture errors and return them as JSON
            return response()->json([
                'success' => false,
                'message' => 'Fingerprint capture failed: ' . $e->getMessage(),
                'error_output' => $process->getErrorOutput(),
            ]);
        }
    }
}
