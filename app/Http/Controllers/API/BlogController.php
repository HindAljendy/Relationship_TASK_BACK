<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlog;
use App\Http\Resources\BlogResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::all();
        return $this->customeRespone(BlogResource::collection($blogs), "All Retrieve Blogs Success", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlog $request)
    {
        try {
            DB::beginTransaction();
            $blog = Blog::create([
                'user_id'        => $request->user_id,
                'title'          => $request->title,
                'body'           => $request->body
            ]);
            DB::commit();

            return $this->customeRespone(new BlogResource($blog),'the Blog created successfully',201);


        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return $this->customeRespone('',' the Blog  not created',500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return $this->customeRespone(new BlogResource($blog), 'ok', 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title'  => 'nullable|string|max:10',
            'body'   => 'nullable|string|max:80',
        ]);

        $newData = [];
        if (isset($request->title)) {
            $newData['title'] = $request->title;
        }
        if (isset($request->body)) {
            $newData['body'] = $request->body;
        }

        $blog->update($newData);

        return $this->customeRespone(new BlogResource($blog), 'Blog Updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return $this->customeRespone('', 'Blog deleted successfully', 200);
    }
}
