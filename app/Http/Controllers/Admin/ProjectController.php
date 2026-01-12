<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'source_link' => 'nullable|string', // assoupli pour éviter blocage
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            try {
                $data['image'] = $this->uploadToCloudinary($file);
            } catch (\Throwable $e) {
                Log::error('Cloudinary upload failed', ['message' => $e->getMessage()]);
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
                $data['image'] = $this->uploadToCloudinary($file);
            } catch (\Throwable $e) {
                Log::error('Cloudinary upload failed', ['message' => $e->getMessage()]);
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
    private function uploadToCloudinary($file)
    {
        if (! $file || ! $file->isValid()) {
            throw new \RuntimeException('Fichier image invalide ou manquant.');
        }


        // Si Cloudinary n'est pas configuré, sauvegarde localement dans storage/app/public/projects
        if (! env('CLOUDINARY_URL')) {
            $path = $file->store('projects', 'public');
            return $path; // stocke le chemin relatif (projects/xxx.jpg)
        }

        // Utilise uploadFile (compatible avec l'ancienne API) mais gère les différents retours.
        $result = Cloudinary::uploadFile($file->getRealPath(), ['folder' => 'projects']);

        if (is_array($result)) {
            if (! empty($result['secure_url'])) {
                return $result['secure_url'];
            }
            if (! empty($result['url'])) {
                return $result['url'];
            }
        }

        if (is_object($result)) {
            if (method_exists($result, 'getSecurePath')) {
                return $result->getSecurePath();
            }

            // certaines versions renvoient un objet ArrayAccess / StdClass
            $asArray = (array) $result;
            if (! empty($asArray['secure_url'])) {
                return $asArray['secure_url'];
            }
            if (! empty($asArray['url'])) {
                return $asArray['url'];
            }
        }

            throw new \RuntimeException('Upload Cloudinary : réponse inattendue.');
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
