<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        try {
            // You can implement email sending here
            // For now, we'll just log the contact attempt and show success
            
            Log::info('Contact form submission', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => substr($validated['message'], 0, 100) . '...',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            // Here you would typically send an email:
            /*
            Mail::send('emails.contact', $validated, function($message) use ($validated) {
                $message->to('support@expensemate.com')
                        ->subject('New Contact Form Submission: ' . $validated['subject'])
                        ->replyTo($validated['email'], $validated['name']);
            });
            */

            return back()->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email']
            ]);

            return back()->with('error', 'Sorry, there was an issue sending your message. Please try again or contact us directly.')
                        ->withInput();
        }
    }

    public function support()
    {
        return view('pages.support');
    }

    public function helpCenter()
    {
        return view('pages.help-center');
    }

    public function documentation()
    {
        return view('pages.documentation');
    }

    public function apiReference()
    {
        return view('pages.api-reference');
    }

    public function tutorials()
    {
        return view('pages.tutorials');
    }

    public function blog()
    {
        return view('pages.blog');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function cookies()
    {
        return view('pages.cookies');
    }
}