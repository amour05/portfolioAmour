<?php
namespace App\Http\Controllers;

use App\Models\Project; // <-- ajoute l'import
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home() {
        return view('home');
    }

    public function about() {
        return view('about');
    }

    public function skills() {
        return view('skills');
    }

   public function projects()
{
    // Récupère uniquement les projets publiés, triés par date, avec pagination
    $projects = Project::where('is_published', true) // <-- si tu as ce champ
        ->orderBy('created_at', 'desc')
        ->paginate(9); // 9 projets par page

    return view('projects', compact('projects'));
}

    public function contact() {
        return view('contact');
    }
}
