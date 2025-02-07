<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoomImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => 'errors',
            'message' => 'Only POST & PUT Allowed.'
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'status' => 'errors',
            'message' => 'Only POST & PUT Allowed.'
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
