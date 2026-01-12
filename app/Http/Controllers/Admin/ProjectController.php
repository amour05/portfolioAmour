<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'source_link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {

            // Sécurité : Cloudinary doit être configuré
            if (!env('CLOUDINARY_URL')) {
                return back()->withErrors([
                    'image' => 'Cloudinary n’est pas configuré sur le serveur.'
                ])->withInput();
            }

            try {
                // Upload vers Cloudinary
                $uploadedFile = Cloudinary::upload(
                    $request->file('image')->getRealPath(),
                    ['folder' => 'projects']
                );

                $data['image'] = $uploadedFile->getSecurePath(); // URL complète Cloudinary

            } catch (\Exception $e) {
                return back()->withErrors([
                    'image' => 'Erreur lors de l’upload de l’image : ' . $e->getMessage()
                ])->withInput();
            }
        }

        Project::create($data);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Projet ajouté avec succès');
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'source_link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {

            if (!env('CLOUDINARY_URL')) {
                return back()->withErrors([
                    'image' => 'Cloudinary n’est pas configuré sur le serveur.'
                ])->withInput();
            }

            try {
                // Upload nouvelle image vers Cloudinary
                $uploadedFile = Cloudinary::upload(
                    $request->file('image')->getRealPath(),
                    ['folder' => 'projects']
                );

                $data['image'] = $uploadedFile->getSecurePath();

            } catch (\Exception $e) {
                return back()->withErrors([
                    'image' => 'Erreur lors de l’upload de l’image : ' . $e->getMessage()
                ])->withInput();
            }
        }

        $project->update($data);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Projet mis à jour');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        // ⚠️ Optionnel : supprimer l’image Cloudinary
        // Cloudinary::destroy($publicId);
        // (il faudrait stocker le public_id en DB si tu veux gérer ça)

        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Projet supprimé');
    }
}