<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
            // Upload vers Cloudinary
            $uploadedFileUrl = Cloudinary::upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'projects'] // Cloudinary crée automatiquement le dossier
            )->getSecurePath();

            $data['image'] = $uploadedFileUrl; // URL complète Cloudinary
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
            // Upload nouvelle image vers Cloudinary
            $uploadedFileUrl = Cloudinary::upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'projects']
            )->getSecurePath();

            $data['image'] = $uploadedFileUrl;
        }

        $project->update($data);
        return redirect()->route('admin.projects.index')->with('success','Projet mis à jour');
    }

    public function destroy($id) {
        $project = Project::findOrFail($id);

        // ⚠️ Optionnel : supprimer l’image Cloudinary
        // Cloudinary::destroy($publicId); 
        // (il faudrait stocker le public_id en DB si tu veux gérer ça)

        $project->delete();
        return redirect()->route('admin.projects.index')->with('success','Projet supprimé');
    }
}
