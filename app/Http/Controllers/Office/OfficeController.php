<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Office\OfficeModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index(): JsonResponse
    {
        $offices = OfficeModel::latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $offices,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'city'    => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'status'  => ['required', 'in:Active,Inactive'],
        ]);

        $validated['name']    = strtoupper($validated['name']);
        $validated['city']    = strtoupper($validated['city']);
        $validated['address'] = strtoupper($validated['address']);

        $office = OfficeModel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Office successfully create ho gaya!',
            'data'    => $office,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $office = OfficeModel::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $office,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $office = OfficeModel::findOrFail($id);

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'city'    => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'status'  => ['required', 'in:Active,Inactive'],
        ]);

        $validated['name']    = strtoupper($validated['name']);
        $validated['city']    = strtoupper($validated['city']);
        $validated['address'] = strtoupper($validated['address']);

        $office->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Office successfully update ho gaya!',
            'data'    => $office,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $office = OfficeModel::findOrFail($id);
        $office->delete();

        return response()->json([
            'success' => true,
            'message' => 'Office successfully delete ho gaya!',
        ]);
    }
}
