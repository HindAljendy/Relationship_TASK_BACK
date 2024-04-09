<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::with('books')->get();
        return response()->json(['authors' => $authors], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Author $author)
    {
        $books  = [1, 2, 4];
        $author->books()->attach($books);
        return response()->json('Author attached to book successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $books  = [3, 6];
        $author->books()->sync($books);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Author $author)
    {
        $author->books()->detach();
        $author->delete();
        return response()->json('Author detached from book successfully');
    }

}
