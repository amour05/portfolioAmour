<?php
namespace App\Http\Controllers;

use App\Models\Project; // <-- ajoute l'import
use Illuminate\Http\Request;
use App\Models\Post;
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

    public function blog()
    {
        $posts = Post::where('published', true)->latest()->paginate(6);
        return view('blog.index', compact('posts'));
    }

    public function showPost($slug)
    {
        $post = Post::where('slug', $slug)->where('published', true)->firstOrFail();
        return view('blog.show', compact('post'));
    }
}
