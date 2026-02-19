<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

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
            'langages' => 'nullable|string',
            'framework' => 'nullable|string',
            'outils' => 'nullable|string',
            'environnement' => 'nullable|string',
            'database' => 'nullable|string',
            'source_link' => 'nullable|url',
        ]);

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
            'langages' => 'nullable|string',
            'framework' => 'nullable|string',
            'outils' => 'nullable|string',
            'environnement' => 'nullable|string',
            'database' => 'nullable|string',
            'source_link' => 'nullable|url',
        ]);

        $project->update($data);
        return redirect()->route('admin.projects.index')->with('success','Projet mis à jour');
    }

    public function destroy($id) {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success','Projet supprimé');
    }
}
