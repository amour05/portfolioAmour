<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index() {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    public function create() {
        return view('admin.projects.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'source_link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            // Stocker l'image dans storage/app/public/projects
            $path = $request->file('image')->store('projects', 'public');
            $data['image'] = $path; // Exemple : projects/nomfichier.jpg
        }

        Project::create($data);
        return redirect()->route('admin.projects.index')->with('success','Projet ajouté avec succès');
    }

    public function edit($id) {
        $project = Project::findOrFail($id);
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, $id) {
        $project = Project::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'source_link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l’ancienne image si elle existe
            if ($project->image && Storage::disk('public')->exists($project->image)) {
                Storage::disk('public')->delete($project->image);
            }

            // Stocker la nouvelle image
            $path = $request->file('image')->store('projects', 'public');
            $data['image'] = $path;
        }

        $project->update($data);
        return redirect()->route('admin.projects.index')->with('success','Projet mis à jour');
    }

    public function destroy($id) {
        $project = Project::findOrFail($id);

        // Supprimer l’image associée si elle existe
        if ($project->image && Storage::disk('public')->exists($project->image)) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();
        return redirect()->route('admin.projects.index')->with('success','Projet supprimé');
    }
}
