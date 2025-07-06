<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function submitContactForm(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Store in the database (optional)
        // ContactMessage::create($request->all()); // Uncomment if using a database

        // Send email notification (optional)
        try {
            Mail::raw("Message from {$request->name}: \n\n{$request->message}", function ($mail) use ($request) {
                $mail->to('admin@example.com')
                     ->subject($request->subject)
                     ->from($request->email, $request->name);
            });

            return response()->json(['success' => 'Your message has been sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send message. Please try again.']);
        }
    }

    public function subscribe(Request $request)
    {
        // Validate email input
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        // Store email in the database (create 'newsletters' table first)
        DB::table('newsletters')->insert([
            'email' => $request->email,
            'created_at' => now(),
        ]);

        return response()->json(['success' => 'You have successfully subscribed to our newsletter!']);
    }
}
