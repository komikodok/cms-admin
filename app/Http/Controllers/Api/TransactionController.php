<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::all();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully fetched transactions data',
            'data' => $transactions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|integer|exists:tenants,id',
            'room_id' => 'required|integer|exists:rooms,id',
            'start_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'status' => 'required|string|in:pending,confirmed,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed creating a new transaction',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        do {
            $token = Str::random(5);
        } while (Transaction::where('token', $token)->exists());
        
        try {
            Transaction::create([
                'tenant_id' => $request->tenant_id,
                'room_id' => $request->room_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed creating a new transaction',
                'errors' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully creating a new transaction',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transactions = Transaction::where('id', $id)->firstOrFail();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully fetched a room data',
            'data' => $transactions
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|integer|exists:tenants,id',
            'room_id' => 'required|integer|exists:rooms,id',
            'start_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'status' => 'required|string|in:pending,confirmed,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed updating a new transaction',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        try {
            $transaction = Transaction::where('id', $id)->firstOrFail;
            $transaction->update($validator->validated());
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed updating a new transaction',
                'errors' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully updating a new transaction',
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::where('id', $id)->firstOrFail();
        $transaction->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully deleting a transaction data',
            'data' => $transaction
        ], Response::HTTP_OK);
    }
}
