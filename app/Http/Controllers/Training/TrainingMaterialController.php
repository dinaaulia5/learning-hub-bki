<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingMaterialRequest;
use App\Http\Resources\TrainingMaterialResource;
use App\Models\TrainingMaterial;
use App\Models\TrainingModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrainingMaterialController extends Controller
{
    /**
     * Menampilkan daftar materi dalam satu modul.
     */
    public function index(
        Request $request,
        string $trainingModule
    ): Response {
        $module = TrainingModule::query()
            ->findOrFail($trainingModule);

        $materials = TrainingMaterial::query()
            ->where('module_id', $module->id)
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(
                    'title',
                    'ilike',
                    '%' . $request->search . '%'
                );
            })
            ->orderBy('sequence')
            ->paginate($request->integer('load', 10))
            ->withQueryString();

        return Inertia::render('TrainingMaterials/Index', [
            'module' => [
                'id' => $module->id,
                'title' => $module->title,
                'training_id' => $module->training_id,
            ],
            'materials' => TrainingMaterialResource::collection($materials),
            'state' => [
                'search' => $request->search ?? '',
                'load' => $request->integer('load', 10),
            ],
        ]);
    }

    /**
     * Menampilkan form tambah materi.
     */
    public function create(string $trainingModule): Response
    {
        $module = TrainingModule::query()
            ->findOrFail($trainingModule);

        return Inertia::render('TrainingMaterials/Create', [
            'module' => [
                'id' => $module->id,
                'title' => $module->title,
                'training_id' => $module->training_id,
            ],
        ]);
    }

    /**
     * Menyimpan materi baru.
     */
    public function store(
        TrainingMaterialRequest $request,
        string $trainingModule
    ): RedirectResponse {
        $module = TrainingModule::query()
            ->findOrFail($trainingModule);

        $module->materials()->create(
            $request->validated()
        );

        return redirect()
            ->route('trainings.show', $module->training_id)
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail materi.
     */
    public function show(
        string $trainingModule,
        string $trainingMaterial
    ): Response {
        $material = $this->findMaterial(
            $trainingModule,
            $trainingMaterial
        );

        return Inertia::render('TrainingMaterials/Show', [
            'material' => new TrainingMaterialResource($material),
        ]);
    }

    /**
     * Menampilkan form edit materi.
     */
    public function edit(
        string $trainingModule,
        string $trainingMaterial
    ): Response {
        $material = $this->findMaterial(
            $trainingModule,
            $trainingMaterial
        );

        return Inertia::render('TrainingMaterials/Edit', [
            'module' => [
                'id' => $material->module_id,
                'title' => $material->module->title,
                'training_id' => $material->module->training_id,
            ],
            'material' => new TrainingMaterialResource($material),
        ]);
    }

    /**
     * Memperbarui materi.
     */
    public function update(
        TrainingMaterialRequest $request,
        string $trainingModule,
        string $trainingMaterial
    ): RedirectResponse {
        $material = $this->findMaterial(
            $trainingModule,
            $trainingMaterial
        );

        $material->update(
            $request->validated()
        );

        return redirect()
            ->route(
                'training-modules.materials.index',
                $material->module_id
            )
            ->with('success', 'Materi berhasil diperbarui.');
    }

    /**
     * Menghapus materi.
     */
    public function destroy(
        string $trainingModule,
        string $trainingMaterial
    ): RedirectResponse {
        $material = $this->findMaterial(
            $trainingModule,
            $trainingMaterial
        );

        $moduleId = $material->module_id;

        $material->delete();

        return redirect()
            ->route(
                'training-modules.materials.index',
                $moduleId
            )
            ->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Memastikan materi memang milik modul pada URL.
     */
    private function findMaterial(
        string $trainingModule,
        string $trainingMaterial
    ): TrainingMaterial {
        return TrainingMaterial::query()
            ->where('module_id', $trainingModule)
            ->with('module')
            ->findOrFail($trainingMaterial);
    }
}
