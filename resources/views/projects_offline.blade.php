@php
    
public function projects()
{
    try {
        $projects = Project::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(9);
    } catch (\Illuminate\Database\QueryException $e) {
        // Log l'erreur et afficher une page d'attente ou message convivial
        \Log::error('DB connection error on projects: '.$e->getMessage());
        $projects = collect(); // collection vide
        return view('projects_offline'); // ou view('projects', ['projects' => $projects])
    }

    return view('projects', compact('projects'));
}
@endphp