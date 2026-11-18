<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingCategoryRequest;
use App\Http\Resources\TrainingCategoryResource;
use App\Models\TrainingCategory;
use App\Supports\Pages\TrainingCategoryPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Response;
use Throwable;

class TrainingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $categories = TrainingCategory::query()
            ->select(['id', 'code', 'name', 'description', 'is_active'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Training/Category/Index', [
            'pageSettings' => fn() => TrainingCategoryPage::index(),
            'categories' => fn() => TrainingCategoryResource::collection($categories)->additional([
                'meta' => [
                    'has_pages' => $categories->hasPages(),
                ],
            ]),
            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ],
            'items' => fn() => TrainingCategoryPage::breadcrumbs()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainingCategoryRequest $request): RedirectResponse
    {
        try {
            TrainingCategory::create($request->validated());

            return back()->with(
                'success',
                'Training category berhasil ditambahkan.'
            );
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan training category.', [
                'message' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Terjadi kesalahan saat menyimpan data.'
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        TrainingCategoryRequest $request,
        TrainingCategory $category
    ): RedirectResponse {
        try {
            $category->update($request->validated());

            return back()->with(
                'success',
                'Training category berhasil diperbarui.'
            );
        } catch (Throwable $e) {
            Log::error('Gagal mengubah training category.', [
                'message' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Terjadi kesalahan saat mengubah data.'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingCategory $category): RedirectResponse
    {
        try {
            $category->delete();

            return back()->with(
                'success',
                'Training category berhasil dihapus.'
            );
        } catch (Throwable $e) {
            Log::error('Gagal menghapus training category.', [
                'message' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Terjadi kesalahan saat menghapus data.'
            );
        }
    }
}
