<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ConfirmTransactionController extends Controller
{
    public function confirm(string $id) {
        $transaction = Transaction::with('payment')->where('id', $id)->firstOrFail();

        $payment = $transaction->payment;
        if (!$payment) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Payment data not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            // DB::beginTransaction();

            // $payment->status === 'success'
            //     ? $transaction->update(['status' => 'confirmed']) 
            //     : $transaction->update(['status' => 'canceled']);

            $transaction->update(['status' => 'confirmed']);
            $payment->update(['status' => 'success']);

            // DB::commit();
        } catch (\Exception $e) {
            // DB::rollBack();

            $transaction->update(['status' => 'canceled']);
            $payment->update(['status' => 'failed']);
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed to update transaction status.',
                'errors' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Status updated successfully.'
        ], Response::HTTP_OK);
    }
}
