<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully fetched payments data.',
            'data' => $payments
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|integer|exists:transactions,id',
            'payment_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'amount' => 'nullable|numeric',
            'status' => 'required|string|in:pending,success,failed',
            'payment_method' => 'required|string|in:transfer,cash'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Validation errors.',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            Payment::create($validator->validated());
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed to record payment data.',
                'errors' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Payment data recorded successfully',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::where('id', $id)->firstOrFail();

        return response()->json([
            'status' => 'ok',
            'message' => 'Successfully fetched a payment data',
            'data' => $payment
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|integer|exists:transactions,id',
            'payment_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'amount' => 'nullable|numeric',
            'status' => 'required|string|in:pending,success,failed',
            'payment_method' => 'required|string|in:transfer,cash'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Validation errors.',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $payment = Payment::where('id', $id)->firstOrFail();
            $payment->update($validator->validated());
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed to update payment data.',
                'errors' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Payment data updated successfully.',
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::where('id', $id)->firstOrFail();
        $payment->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Payment data deleted successfully',
        ], Response::HTTP_OK);
    }
}
