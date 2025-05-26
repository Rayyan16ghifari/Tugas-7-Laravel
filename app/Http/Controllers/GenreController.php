<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    // GET /api/genres
    public function index()
    {
        $genres = Genre::all();
        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    // POST /api/genres
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "description" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ], 422);
        }

        $genre = Genre::create([
            "name" => $request->name,
            "description" => $request->description
        ]);

        return response()->json([
            "success" => true,
            "message" => "Genre created successfully!",
            "data" => $genre
        ], 201);
    }

    // GET /api/genres/{id}
    public function show($id)
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return response()->json([
                "success" => false,
                "message" => "Genre not found"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "data" => $genre
        ]);
    }

    // PUT /api/genres/{id}
    public function update(Request $request, $id)
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return response()->json([
                "success" => false,
                "message" => "Genre not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            "name" => "sometimes|required|string",
            "description" => "sometimes|required|string"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ], 422);
        }

        $genre->update($request->only('name', 'description'));

        return response()->json([
            "success" => true,
            "message" => "Genre updated successfully!",
            "data" => $genre
        ]);
    }

    // DELETE /api/genres/{id}
    public function destroy($id)
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return response()->json([
                "success" => false,
                "message" => "Genre not found"
            ], 404);
        }

        $genre->delete();

        return response()->json([
            "success" => true,
            "message" => "Genre deleted successfully!"
        ]);
    }
}
