<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
                $file = $request->file('image');
                if (!method_exists($file, 'isValid') || !$file->isValid()) {
                    return back()->withErrors(['image' => 'Fichier image invalide ou corrompu.'])->withInput();
                }

                // Déterminer un chemin réel vers le fichier (getRealPath peut retourner null)
                $realPath = $file->getRealPath();
                if (!$realPath || !file_exists($realPath)) {
                    $realPath = $file->getPathname() ?: null;
                }

                $tmpCreated = false;
                if (!$realPath || !file_exists($realPath)) {
                    try {
                        $tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('upload_') . '.' . ($file->getClientOriginalExtension() ?: 'tmp');
                        file_put_contents($tmpPath, $file->get());
                        $realPath = $tmpPath;
                        $tmpCreated = true;
                    } catch (\Throwable $e) {
                        Log::error('Failed to create temp file for upload', ['message' => $e->getMessage()]);
                        return back()->withErrors(['image' => 'Impossible de traiter le fichier image.'])->withInput();
                    }
                }

                Log::info('Preparing Cloudinary upload', ['realPath' => $realPath, 'exists' => file_exists($realPath)]);

                // Upload vers Cloudinary
                $uploadedFile = Cloudinary::upload(
                    $realPath,
                    ['folder' => 'projects']
                );

                // Supporter différentes formes de retour (array ou objet selon la lib)
                if (is_array($uploadedFile) && isset($uploadedFile['secure_url'])) {
                    $data['image'] = $uploadedFile['secure_url'];
                } elseif (is_object($uploadedFile) && method_exists($uploadedFile, 'getSecurePath')) {
                    $data['image'] = $uploadedFile->getSecurePath();
                } elseif (is_object($uploadedFile) && method_exists($uploadedFile, 'getSecureUrl')) {
                    $data['image'] = $uploadedFile->getSecureUrl();
                } else {
                    Log::error('Cloudinary upload returned unexpected result', ['result' => $uploadedFile]);
                    if (isset($tmpCreated) && $tmpCreated && isset($tmpPath) && file_exists($tmpPath)) {
                        @unlink($tmpPath);
                    }
                    return back()->withErrors(['image' => 'Erreur lors de l’upload de l’image (réponse inattendue).'])->withInput();
                }

            } catch (\Throwable $e) {
                Log::error('Cloudinary upload failed', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                if (isset($tmpCreated) && $tmpCreated && isset($tmpPath) && file_exists($tmpPath)) {
                    @unlink($tmpPath);
                }
                return back()->withErrors([
                    'image' => 'Erreur lors de l’upload de l’image : ' . $e->getMessage()
                ])->withInput();
            }
            if (isset($tmpCreated) && $tmpCreated && isset($tmpPath) && file_exists($tmpPath)) {
                @unlink($tmpPath);
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
            'source_link' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {

            if (!env('CLOUDINARY_URL')) {
                return back()->withErrors([
                    'image' => 'Cloudinary n’est pas configuré sur le serveur.'
                ])->withInput();
            }

            try {
                $file = $request->file('image');
                if (!method_exists($file, 'isValid') || !$file->isValid()) {
                    return back()->withErrors(['image' => 'Fichier image invalide ou corrompu.'])->withInput();
                }

                // Upload nouvelle image vers Cloudinary
                $uploadedFile = Cloudinary::upload(
                    $file->getRealPath(),
                    ['folder' => 'projects']
                );

                if (is_array($uploadedFile) && isset($uploadedFile['secure_url'])) {
                    $data['image'] = $uploadedFile['secure_url'];
                } elseif (is_object($uploadedFile) && method_exists($uploadedFile, 'getSecurePath')) {
                    $data['image'] = $uploadedFile->getSecurePath();
                } elseif (is_object($uploadedFile) && method_exists($uploadedFile, 'getSecureUrl')) {
                    $data['image'] = $uploadedFile->getSecureUrl();
                } else {
                    Log::error('Cloudinary upload returned unexpected result', ['result' => $uploadedFile]);
                    return back()->withErrors(['image' => 'Erreur lors de l’upload de l’image (réponse inattendue).'])->withInput();
                }

            } catch (\Exception $e) {
                Log::error('Cloudinary upload failed', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
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
