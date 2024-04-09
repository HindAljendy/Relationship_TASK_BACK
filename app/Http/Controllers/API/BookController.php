<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBook;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $books = Book::all();
        return $this->customeRespone($books, "All Retrieve Books Success", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBook $request, Book $book)
    {
        try {
            DB::beginTransaction();
            $book = Book::create([
                'name'          => $request->name,
                'price'         => $request->price
            ]);
            DB::commit();

            return $this->customeRespone($book ,'the book created successfully',201);


        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return $this->customeRespone('',' the book  not created',500);
        }


    }


    /**
     * Display the specified resource.
     */

     public function show(Book $book)
     {
        return $this->customeRespone($book,'ok',200);
     }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'name'          => 'nullable|string',
            'price'         => 'nullable|integer'
        ]);

        $newData=[];
        if(isset($request->name)){
            $newData['name'] = $request->name;
        }
        if(isset($request->price)){
            $newData['price'] = $request->price;
        }


        $book->update($newData);

        return $this->customeRespone($book,' book Updated successfully',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Book $book)
    {
        $book->delete();

        return $this->customeRespone('','book deleted successfully',200);

    }
}
