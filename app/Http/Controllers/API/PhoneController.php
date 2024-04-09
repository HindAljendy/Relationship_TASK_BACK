<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhone;
use App\Http\Resources\PhoneResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PhoneController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phones = Phone::all();
        return $this->customeRespone(PhoneResource::collection($phones), "All Retrieve Phones Success", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhone $request)
    {
        try {
            DB::beginTransaction();

            $phone = Phone::create([
                'user_id'      => $request->user_id,
                'number_phone' => $request->number_phone
            ]);

            DB::commit();

            return $this->customeRespone(new PhoneResource($phone), 'the Phone created successfully', 201);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return $this->customeRespone('', 'the Phone  not created', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Phone $phone)
    {
        return $this->customeRespone(new PhoneResource($phone), 'ok', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phone $phone)
    {
        $request->validate([
            'number_phone'  => 'nullable|string|max:50',
        ]);

        $newData = [];
        if (isset($request->number_phone)) {
            $newData['number_phone'] = $request->number_phone;
        }

        $phone->update($newData);

        return $this->customeRespone(new PhoneResource($phone), 'Phone Updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phone $phone)
    {
        $phone->delete();

        return $this->customeRespone('', 'Phone deleted successfully', 200);
    }
}
