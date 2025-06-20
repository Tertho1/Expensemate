<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Here you can send email or store in database
        // For now, just redirect with success message
        return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
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