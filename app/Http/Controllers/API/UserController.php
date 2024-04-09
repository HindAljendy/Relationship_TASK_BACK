<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return $this->customeRespone(UserResource::collection($users), "All Retrieve Users Success", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUser $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'password'    => $request->password
            ]);
            DB::commit();

            return $this->customeRespone(new UserResource($user),'the User created successfully',201);


        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return $this->customeRespone('',' the User  not created',500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->customeRespone(new UserResource($user),'ok',200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,User $user )
    {
        $request->validate([
            'name'          => 'nullable|string|max:50',
            'email'         => 'nullable|string|email|unique:users',
            'password'      => 'nullable|min:8'
        ]);

        $newData=[];
        if(isset($request->name)){
            $newData['name'] = $request->name;
        }
        if(isset($request->email)){
            $newData['email'] = $request->email;
        }
        if(isset($request->password)){
            $newData['password'] = $request->password;
        }

        $user->update($newData);

        return $this->customeRespone(new UserResource($user),'User Updated successfully',200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->customeRespone('','User deleted successfully',200);

    }
}
