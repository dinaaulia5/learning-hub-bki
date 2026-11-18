<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Response;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $employees = Employee::query()
            ->select(['id', 'name', 'auth_id', 'nup', 'division', 'position', 'departement_code', 'employee_status', 'is_active'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('User/Employee/Index', [
            'pageSettings' => fn() => TrainingPage::index(),
            'employees' => fn() => TrainingResource::collection($employees)->additional([
                'meta' => [
                    'has_pages' => $employees->hasPages(),
                ],
            ]),
            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ],
            'items' => fn() => TrainingPage::breadcrumbs()
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
