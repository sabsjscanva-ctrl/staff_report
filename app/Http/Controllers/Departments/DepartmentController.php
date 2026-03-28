<?php

namespace App\Http\Controllers\Departments;

use App\Http\Controllers\Controller;
use App\Models\Office\DepartmentModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(): JsonResponse
    {
        $departments = DepartmentModel::latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $departments,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $validated['name'] = strtoupper($validated['name']);

        $department = DepartmentModel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Department successfully create ho gaya!',
            'data'    => $department,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $department = DepartmentModel::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $department,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $department = DepartmentModel::findOrFail($id);

        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $validated['name'] = strtoupper($validated['name']);

        $department->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Department successfully update ho gaya!',
            'data'    => $department,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $department = DepartmentModel::findOrFail($id);
        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department successfully delete ho gaya!',
        ]);
    }
}
