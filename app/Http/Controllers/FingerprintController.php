<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;


class FingerprintController extends Controller
{

public function capture()
{
    $exePath = base_path('fingerprint/FutronicCapture.exe');
    $workingDir = base_path('fingerprint');
    $dotnetCache = storage_path('dotnet_cache');

    if (!file_exists($dotnetCache)) {
        mkdir($dotnetCache, 0777, true);
    }

    $process = new Process([$exePath]);
    $process->setWorkingDirectory($workingDir);
    $process->setEnv([
        'DOTNET_BUNDLE_EXTRACT_BASE_DIR' => $dotnetCache
    ]);
    $process->setTimeout(30);

    try {
        $process->mustRun();

        return response()->json([
            'success' => true,
            'message' => 'Fingerprint captured successfully.',
            'output'  => $process->getOutput(),
        ]);
    } catch (\Exception $e) {
        // Build full error report
        $errorReport = "Fingerprint capture failed:\n"
            . "Message: " . $e->getMessage() . "\n"
            . "Exit Code: " . $process->getExitCode() . " (" . $process->getExitCodeText() . ")\n"
            . "Working Dir: " . $workingDir . "\n"
            . "Exe Path: " . $exePath . "\n"
            . "=== STDOUT ===\n" . $process->getOutput() . "\n"
            . "=== STDERR ===\n" . $process->getErrorOutput();

        // Log full details to storage/logs/laravel.log
        Log::error($errorReport);

        return response()->json([
            'success' => false,
            'message' => 'Fingerprint capture failed. Check logs for details.',
        ]);
    }
}


}
