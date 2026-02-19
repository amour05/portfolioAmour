<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact; // <-- import correct du modèle

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        // Enregistrement en base via Eloquent
        Contact::create($data);

        return redirect()->route('contact')->with('success', 'Message enregistré avec succès !');
    }
}
