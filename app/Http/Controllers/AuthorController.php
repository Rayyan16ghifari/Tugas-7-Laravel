<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    // GET /api/authors
    public function index()
    {
        $authors = Author::all();
        return response()->json([
            'success' => true,
            'data' => $authors
        ]);
    }

    // POST /api/authors
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "bio" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ], 422);
        }

        $author = Author::create([
            "name" => $request->name,
            "bio" => $request->bio
        ]);

        return response()->json([
            "success" => true,
            "message" => "Author created successfully!",
            "data" => $author
        ], 201);
    }

    // GET /api/authors/{id}
    public function show($id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json([
                "success" => false,
                "message" => "Author not found"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "data" => $author
        ]);
    }

    // PUT /api/authors/{id}
    public function update(Request $request, $id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json([
                "success" => false,
                "message" => "Author not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            "name" => "sometimes|required|string",
            "bio" => "sometimes|required|string"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ], 422);
        }

        $author->update($request->only('name', 'bio'));

        return response()->json([
            "success" => true,
            "message" => "Author updated successfully!",
            "data" => $author
        ]);
    }

    // DELETE /api/authors/{id}
    public function destroy($id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json([
                "success" => false,
                "message" => "Author not found"
            ], 404);
        }

        $author->delete();

        return response()->json([
            "success" => true,
            "message" => "Author deleted successfully!"
        ]);
    }
}
