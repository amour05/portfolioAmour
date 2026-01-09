<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'image' => 'nullable|image',
            'source_link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // nom unique et lisible
            $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                        .'.'.$file->getClientOriginalExtension();

            // stockage dans storage/app/public/projects
            $file->storeAs('projects', $filename, 'public');
            $data['image'] = 'projects/'.$filename;
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
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'image' => 'nullable|image',
            'source_link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            // supprimer l’ancienne image si elle existe
            if ($project->image && Storage::disk('public')->exists($project->image)) {
                Storage::disk('public')->delete($project->image);
            }

            $file = $request->file('image');
            $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                        .'.'.$file->getClientOriginalExtension();

            $file->storeAs('projects', $filename, 'public');
            $data['image'] = 'projects/'.$filename;
        }

        $project->update($data);
        return redirect()->route('admin.projects.index')->with('success','Projet mis à jour');
    }

    public function destroy($id) {
        $project = Project::findOrFail($id);

        // supprimer l’image associée si elle existe
        if ($project->image && Storage::disk('public')->exists($project->image)) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();
        return redirect()->route('admin.projects.index')->with('success','Projet supprimé');
    }
}
