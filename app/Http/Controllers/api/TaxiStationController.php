<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaxiStationResource;
use App\Models\TaxiStation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaxiStationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return TaxiStationResource::collection(TaxiStation::where('user_id', $user->id)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'city' => 'required',
            'address' => 'required',
        ]);

        $user = Auth::user();

        $is_station_setup = $user->is_station_setup;

        if ($is_station_setup) {
            return response()->json(['message' => 'You have already setup a taxi station'], 400);
        }

        $taxiStation = TaxiStation::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city' => $request->city,
            'address' => $request->address,
            'user_id' => $user->id,
        ]);

        User::where('id', $user->id)->update(['is_station_setup' => 1]);

        return new TaxiStationResource($taxiStation);


    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $taxiStation = TaxiStation::where('user_id', $user->id)->find($id);
        if ($taxiStation) {
            return new TaxiStationResource($taxiStation);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxiStation $taxiStation)
    {
        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'status' => 'sometimes',
            'city' => 'required',
            'address' => 'required',
        ]);

        $taxiStation->update($request->all());

        return new TaxiStationResource($taxiStation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $taxiStation = TaxiStation::where('user_id', $user->id)->find($id);
        if ($taxiStation) {
            $taxiStation->delete();
            return response()->json(['message' => 'Taxi station deleted successfully'], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
