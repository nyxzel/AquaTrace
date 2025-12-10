<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show Contact Page
     */
    public function index()
    {
        return view('contactLanding'); // your blade file name (contact.blade.php)
    }

    /**
     * Handle Contact Form Submit
     */
    public function send(Request $request)
    {
        // Validate form inputs
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        // Prepare email data
        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'subject'   => $request->subject,
            'body'      => $request->message,
        ];

        // Send Email
        Mail::send('emails.contact', $data, function ($message) use ($data) {
            $message->to('info@aquatrace.com')        // your receiving email
                ->subject('New Contact Message: ' . $data['subject']);
        });

        // Redirect back with success message
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
