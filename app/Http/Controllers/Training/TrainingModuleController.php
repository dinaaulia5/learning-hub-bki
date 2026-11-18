<?php

namespace App\Http\Controllers\Training;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingModuleRequest;
use App\Http\Resources\TrainingModuleResource;
use App\Models\Training;
use App\Models\TrainingModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Throwable;

class TrainingModuleController extends Controller
{
    public function index(): Response
    {
        $modules = TrainingModule::query()
            ->with(['training:id,code,title'])
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'ilike', "%{$search}%")
                        ->orWhere('description', 'ilike', "%{$search}%")
                        ->orWhereHas('training', function ($trainingQuery) use ($search) {
                            $trainingQuery
                                ->where('title', 'ilike', "%{$search}%")
                                ->orWhere('code', 'ilike', "%{$search}%");
                        });
                });
            })
            ->orderBy('training_id')
            ->orderBy('sequence')
            ->paginate(request()->integer('load', 10))
            ->withQueryString();

        return inertia('TrainingModule/Index', [
            'modules' => TrainingModuleResource::collection($modules)
                ->additional([
                    'meta' => [
                        'has_pages' => $modules->hasPages(),
                    ],
                ]),

            'state' => [
                'page' => request()->integer('page', 1),
                'search' => request('search', ''),
                'load' => request()->integer('load', 10),
            ],
        ]);
    }

    public function create(): Response
    {
        return inertia('TrainingModule/Create', [
            'trainings' => Training::query()
                ->select(['id', 'code', 'title'])
                ->orderBy('title')
                ->get(),
        ]);
    }


    public function store(TrainingModuleRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $training = Training::findOrFail($validated['training_id']);

            TrainingModule::create($validated);

            flashMessage(MessageType::CREATED->message('Modul Training'));

            return redirect()
                ->route('trainings.show', $training->id);
        } catch (Throwable $e) {
            report($e);

            flashMessage(
                MessageType::ERROR->message(
                    error: 'Gagal menyimpan modul training.'
                ),
                'error'
            );

            return back()->withInput();
        }
    }


    public function edit(string $trainingModule): Response
    {
        $module = TrainingModule::query()
            ->findOrFail($trainingModule);

        return inertia('TrainingModule/Edit', [
            'module' => new TrainingModuleResource(
                $module->load('training:id,code,title')
            ),

            'trainings' => Training::query()
                ->select(['id', 'code', 'title'])
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function update(
        TrainingModuleRequest $request,
        string $trainingModule
    ): RedirectResponse {
        try {
            $module = TrainingModule::query()
                ->findOrFail($trainingModule);

            $module->update($request->validated());

            flashMessage(
                MessageType::UPDATED->message('Modul Training')
            );

            return to_route('training-modules.index');
        } catch (Throwable $e) {
            report($e);

            flashMessage(
                MessageType::ERROR->message(
                    error: 'Gagal memperbarui modul training.'
                ),
                'error'
            );

            return back()->withInput();
        }
    }

    public function destroy(
        string $trainingModule
    ): RedirectResponse {
        try {
            $module = TrainingModule::query()
                ->findOrFail($trainingModule);

            $module->delete();

            flashMessage(
                MessageType::DELETED->message('Modul Training')
            );

            return to_route('training-modules.index');
        } catch (Throwable $e) {
            report($e);

            flashMessage(
                MessageType::ERROR->message(
                    error: 'Gagal menghapus modul training.'
                ),
                'error'
            );

            return back();
        }
    }
}
