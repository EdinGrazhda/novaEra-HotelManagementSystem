<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmission;

class ContactController extends Controller
{
    /**
     * Display the contact page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Send email to your email address
            Mail::to('edingrazhda17@gmail.com')
                ->send(new ContactFormSubmission($validated));
            
            // Log successful submission for debugging
            Log::info('Contact form submitted by: ' . $validated['name'] . ' (' . $validated['email'] . ')');
            
            // Success response
            return back()->with('success', 'Thank you for your message! We will get back to you soon.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Contact form email error: ' . $e->getMessage());
            
            // User-friendly error message
            return back()->with('error', 'Sorry, we could not send your message at this time. Please try again later.')
                        ->withInput();
        }
    }
}
