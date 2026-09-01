<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        $contactMessage = ContactMessage::create($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Merci pour votre message ! Notre équipe zizo aura vous répondra sous 24h.',
                'data' => $contactMessage,
            ]);
        }

        return redirect()->route('contact')->with('success', 'Merci pour votre message ! Notre équipe zizo aura vous répondra sous 24h.');
    }
}
