<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'source_link' => 'nullable|string', // assoupli pour éviter blocage
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            try {
                $data['image'] = $this->storeImageLocally($file);
            } catch (\Throwable $e) {
                Log::error('Local image upload failed', ['message' => $e->getMessage()]);
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'source_link' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            try {
                $data['image'] = $this->storeImageLocally($file);
            } catch (\Throwable $e) {
                Log::error('Local image upload failed', ['message' => $e->getMessage()]);
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

    /**
     * Upload helper qui gère différents retours de Cloudinary et vérifie le fichier.
     * Retourne l'URL sécurisée ou lève une exception.
     *
     * @param \Illuminate\Http\UploadedFile|null $file
     * @return string
     * @throws \Throwable
     */
    private function storeImageLocally($file)
    {
        if (! $file || ! $file->isValid()) {
            throw new \RuntimeException('Fichier image invalide ou manquant.');
        }
        // Sauvegarde localement dans storage/app/public/projects et retourne le chemin relatif
        $path = $file->store('projects', 'public');
        if (! $path) {
            throw new \RuntimeException('Impossible d\'enregistrer le fichier.');
        }

        return $path; // exemple: projects/abcd1234.jpg
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        // ⚠️ Optionnel : supprimer l’image Cloudinary si tu stockes le public_id
        // Cloudinary::destroy($publicId);

        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Projet supprimé');
    }
}
