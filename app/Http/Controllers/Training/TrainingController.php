<?php

namespace App\Http\Controllers\Training;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingRequest;
use App\Http\Resources\TrainingModuleResource;
use App\Http\Resources\TrainingResource;
use App\Models\Training;
use App\Models\TrainingCategory;
use App\Models\TrainingModule;
use App\Models\TrainingType;
use App\Supports\Pages\TrainingPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Response;
use Throwable;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $trainings = Training::query()
            ->with([
                'category:id,code,name',
                'type:id,code,name',
            ])
            ->select([
                'id',
                'code',
                'title',
                'slug',
                'description',
                'category_id',
                'type_id',
                'level',
                'duration_minutes',
                'capacity',
                'is_mandatory',
                'status',
                'thumbnail',
                'start_date',
                'end_date',
                'registration_start',
                'registration_end',
                'created_by',
                'created_at',
                'updated_at',
            ])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Training/Index', [
            'pageSettings' => fn() => TrainingPage::index(),

            'trainings' => fn() => TrainingResource::collection($trainings)
                ->additional([
                    'meta' => [
                        'has_pages' => $trainings->hasPages(),
                    ],
                ]),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ],

            'items' => fn() => TrainingPage::breadcrumbs(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $categories = TrainingCategory::query()
            ->select([
                'id',
                'code',
                'name',
            ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $trainingTypes = TrainingType::query()
            ->select([
                'id',
                'code',
                'name',
            ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return inertia('Training/Create', [
            'pageSettings' => fn() => TrainingPage::create(),
            'items' => fn() => TrainingPage::createBreadcrumbs(),

            'categories' => $categories,
            'trainingTypes' => $trainingTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainingRequest $request): RedirectResponse
    {
        try {
            Training::create([
                'category_id' => $request->category_id,
                'type_id' => $request->type_id,

                'code' => $request->code,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,

                'level' => $request->level,
                'duration_minutes' => $request->duration_minutes,
                'capacity' => $request->capacity,

                'is_mandatory' => $request->boolean('is_mandatory'),

                'status' => $request->status,

                'thumbnail' => $request->thumbnail,

                'start_date' => $request->start_date,
                'end_date' => $request->end_date,

                'registration_start' => $request->registration_start,
                'registration_end' => $request->registration_end,

                'created_by' => auth()->id(),
            ]);

            flashMessage(
                MessageType::CREATED->message('Pelatihan')
            );

            return to_route('trainings.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(
                    error: $e->getMessage()
                ),
                'error'
            );

            return to_route('trainings.index');
        }
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id): Response
    {
        $training = Training::query()
            ->with([
                'category:id,code,name',
                'type:id,code,name',
            ])
            ->findOrFail($id);

        $modules = TrainingModule::query()
            ->with('materials')
            ->where('training_id', $training->id)
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Training/Detail', [
            'pageSettings' => fn() => TrainingPage::show($training),

            'items' => fn() => TrainingPage::showBreadcrumbs(),

            'training' => fn() => new TrainingResource($training),

            'modules' => fn() => TrainingModuleResource::collection($modules)
                ->additional([
                    'meta' => [
                        'has_pages' => $modules->hasPages(),
                    ],
                ]),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
                'field' => request()->field ?? 'sequence',
                'direction' => request()->direction ?? 'asc',
            ],
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id): Response
    // {
    //     $training = Training::query()
    //         ->findOrFail($id);

    //     $categories = TrainingCategory::query()
    //         ->select([
    //             'id',
    //             'code',
    //             'name',
    //         ])
    //         ->where('is_active', true)
    //         ->orderBy('name')
    //         ->get();

    //     $trainingTypes = TrainingType::query()
    //         ->select([
    //             'id',
    //             'code',
    //             'name',
    //         ])
    //         ->where('is_active', true)
    //         ->orderBy('name')
    //         ->get();

    //     return inertia('Training/Edit', [
    //         'pageSettings' => fn() => TrainingPage::edit(),
    //         'items' => fn() => TrainingPage::editBreadcrumbs(),

    //         'training' => fn() => new TrainingResource($training),

    //         'categories' => $categories,
    //         'trainingTypes' => $trainingTypes,
    //     ]);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        TrainingRequest $request,
        string $id
    ): RedirectResponse {
        try {
            $training = Training::query()
                ->findOrFail($id);

            $training->update([
                'category_id' => $request->category_id,
                'type_id' => $request->type_id,

                'code' => $request->code,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,

                'level' => $request->level,
                'duration_minutes' => $request->duration_minutes,
                'capacity' => $request->capacity,

                'is_mandatory' => $request->boolean('is_mandatory'),

                'status' => $request->status,

                'thumbnail' => $request->thumbnail,

                'start_date' => $request->start_date,
                'end_date' => $request->end_date,

                'registration_start' => $request->registration_start,
                'registration_end' => $request->registration_end,
            ]);

            flashMessage(
                MessageType::UPDATED->message('Pelatihan')
            );

            return to_route('trainings.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(
                    error: $e->getMessage()
                ),
                'error'
            );

            return to_route('trainings.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $training = Training::query()
                ->findOrFail($id);

            $training->delete();

            flashMessage(
                MessageType::DELETED->message('Pelatihan')
            );

            return to_route('trainings.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(
                    error: $e->getMessage()
                ),
                'error'
            );

            return to_route('trainings.index');
        }
    }
}
