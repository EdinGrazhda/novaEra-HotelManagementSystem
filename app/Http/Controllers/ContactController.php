<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // For now, we'll just return with success (you can implement actual email sending later)
        return back()->with('success', 'Thank you for your message! We will get back to you soon.');

        /* 
         * To implement actual email sending, uncomment this code and create the Mail class:
         *
         * Mail::to('info@novaera-hms.com')->send(new ContactFormSubmission($validated));
         * return redirect()->route('contact.index')->with('success', 'Thank you for your message! We will get back to you soon.');
         */
    }
}
