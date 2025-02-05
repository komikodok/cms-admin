<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::all();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully fetched tenants',
            'data' => $tenants
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required|string',
            'email' => 'required|string|email:rfc,dns',
            'phone_number' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed creating a new tenant',
                'errors' => $validator->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        try {
            $tenant = Tenant::create($validator->validated());            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed creating a new tenant',
                'errors' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully creating a new tenant',
            'data' => $tenant
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tenant = Tenant::where('id', $id)->firstOrFail();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully fetched a tenant data',
            'data' => $tenant
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required|string',
            'email' => 'required|string|email:rfc,dns',
            'phone_number' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed updating a tenant',
                'errors' => $validator->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        try {
            $tenant = Tenant::where('id', $id)->firstOrFail();
            $tenant->update($validator->validated());
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed creating a new tenant',
                'errors' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully updating a tenant',
            'data' => $tenant
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tenant = Tenant::where('id', $id)->firstOrFail();
        $tenant->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully deleting a tenant',
            'data' => $tenant
        ]);
    }
}
