<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\RoomImage;
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
            'message' => 'Only POST & DELETE Allowed.'
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

        $imageName = $request->file('image')->getClientOriginalName();
        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Validation errors.',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $request->file('image')->move(public_path('images'), $imageName);
            RoomImage::create([
                'room_id' => $request->input('room_id'),
                'image' => $request->input('image')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed to upload image data.',
                'errors' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Image data uploaded successfully.',
        ], Response::HTTP_CREATED);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'status' => 'errors',
            'message' => 'Only POST & DELETE Allowed.'
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json([
            'status' => 'errors',
            'message' => 'Only POST & DELETE Allowed.'
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        $roomImage = RoomImage::where('id', $id)->firstOrFail();
        $roomImage->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Image data deleted successfully.'
        ]);
    }
}
