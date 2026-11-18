<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingTypeRequest;
use App\Http\Resources\TrainingTypeResource;
use App\Models\TrainingType;
use App\Supports\Pages\TrainingTypePage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Response;
use Throwable;

class TrainingTypeController extends Controller
{
    public function index(): Response
    {
        $types = TrainingType::query()
            ->select(['id', 'name', 'description', 'code', 'color', 'is_active'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Training/Type/Index', [
            'pageSettings' => fn() => TrainingTypePage::index(),
            'types' => fn() => TrainingTypeResource::collection($types)->additional([
                'meta' => [
                    'has_pages' => $types->hasPages(),
                ],
            ]),
            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ],
            'items' => fn() => TrainingTypePage::breadcrumbs()
        ]);
    }

    public function store(TrainingTypeRequest $request): RedirectResponse
    {
        try {
            TrainingType::create($request->validated());

            return back()->with(
                'success',
                'Training type berhasil ditambahkan.'
            );
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan training type.', [
                'message' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Terjadi kesalahan saat menyimpan data.'
            );
        }
    }

    public function update(TrainingTypeRequest $request, TrainingType $type): RedirectResponse
    {
        try {
            $type->update($request->validated());

            return back()->with(
                'success',
                'Training type berhasil diperbarui.'
            );
        } catch (Throwable $e) {
            Log::error('Gagal mengubah training type.', [
                'message' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Terjadi kesalahan saat mengubah data.'
            );
        }
    }

    public function destroy(TrainingType $type): RedirectResponse
    {
        try {
            $type->delete();

            return back()->with(
                'success',
                'Training type berhasil dihapus.'
            );
        } catch (Throwable $e) {
            Log::error('Gagal menghapus training type.', [
                'message' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Terjadi kesalahan saat menghapus data.'
            );
        }
    }
}
