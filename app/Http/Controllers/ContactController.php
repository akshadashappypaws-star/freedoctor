<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Show the contact page
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|in:general,technical,billing,partnership,feedback,other',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors below.');
        }

        try {
            // Store contact submission in database (optional)
            $this->storeContactSubmission($request->all());

            // Send email notification
            $this->sendContactEmail($request->all());

            return redirect()->back()->with('success', 'Thank you for your message! We will get back to you within 24 hours.');

        } catch (\Exception $e) {
            Log::error('Contact form submission error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sorry, there was an error sending your message. Please try again or contact us directly.');
        }
    }

    /**
     * Store contact submission in database
     */
    private function storeContactSubmission($data)
    {
        try {
            DB::table('contact_submissions')->insert([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // If table doesn't exist, create it
            $this->createContactSubmissionsTable();
            
            // Try again
            DB::table('contact_submissions')->insert([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Create contact submissions table if it doesn't exist
     */
    private function createContactSubmissionsTable()
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS contact_submissions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone VARCHAR(20) NULL,
                subject ENUM('general', 'technical', 'billing', 'partnership', 'feedback', 'other') NOT NULL,
                message TEXT NOT NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                status ENUM('new', 'in_progress', 'resolved') DEFAULT 'new',
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX idx_email (email),
                INDEX idx_subject (subject),
                INDEX idx_status (status),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    /**
     * Send contact email notification
     */
    private function sendContactEmail($data)
    {
        $adminEmail = env('WHATSAPP_NOTIFICATION_EMAIL', 'admin@freedoctor.in');
        
        // Email to admin
        Mail::send('emails.contact-notification', ['data' => $data], function ($message) use ($adminEmail, $data) {
            $message->to($adminEmail)
                ->subject('New Contact Form Submission - ' . ucfirst($data['subject']))
                ->from($data['email'], $data['name']);
        });

        // Auto-reply to user
        Mail::send('emails.contact-auto-reply', ['data' => $data], function ($message) use ($data) {
            $message->to($data['email'], $data['name'])
                ->subject('Thank you for contacting FreeDoctor')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });
    }

    /**
     * Get contact statistics for admin
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total' => DB::table('contact_submissions')->count(),
                'today' => DB::table('contact_submissions')->whereDate('created_at', today())->count(),
                'this_week' => DB::table('contact_submissions')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'pending' => DB::table('contact_submissions')->where('status', 'new')->count(),
                'by_subject' => DB::table('contact_submissions')
                    ->select('subject', DB::raw('COUNT(*) as count'))
                    ->groupBy('subject')
                    ->get()
                    ->pluck('count', 'subject')
                    ->toArray(),
            ];

            return response()->json(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
