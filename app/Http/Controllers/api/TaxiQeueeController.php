<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaxiQeueeResource;
use App\Models\TaxiQeuee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaxiQeueeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return TaxiQeueeResource::collection(TaxiQeuee::where('user_id', $user->id)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'taxi_number' => 'required|numeric',
            'from' => 'required',
            'to' => 'required',
            'passengers' => 'required',
        ]);

        $taxiQeuee = TaxiQeuee::create([
            'taxi_number' => $request->taxi_number,
            'enter_time' => Carbon::now(),
            'exit_time' => $request->exit_time,
            'from' => $request->from,
            'to' => $request->to,
            'passengers' => $request->passengers,
            'user_id' => Auth::id(),
        ] );

        return new TaxiQeueeResource($taxiQeuee);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $taxiQeuee = TaxiQeuee::where('user_id', $user->id)->find($id);
        if ($taxiQeuee) {
            return new TaxiQeueeResource($taxiQeuee);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $taxiQeuee = TaxiQeuee::where('user_id', $user->id)->find($id);
        if ($taxiQeuee) {
            $taxiQeuee->update($request->all());
            return new TaxiQeueeResource($taxiQeuee);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $taxiQeuee = TaxiQeuee::where('user_id', $user->id)->find($id);
        if ($taxiQeuee) {
            $taxiQeuee->delete();
            return response()->json(['message' => 'TaxiQeuee deleted successfully']);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // get deleted records
    public function getDeleted()
    {
        $user = Auth::user();
        return TaxiQeueeResource::collection(TaxiQeuee::where('user_id', $user->id)->onlyTrashed()->get());
    }

    // restore deleted records
    public function restoreDeleted($id)
    {
        $user = Auth::user();
        $taxiQeuee = TaxiQeuee::where('user_id', $user->id)->onlyTrashed()->find($id);
        $taxiQeuee->restore();
        return response()->json(['message' => 'TaxiQeuee restored successfully']);
    }

    // permanently delete records
    public function deletePermanently($id)
    {
        $user = Auth::user();
        $taxiQeuee = TaxiQeuee::where('user_id', $user->id)->onlyTrashed()->find($id);
        $taxiQeuee->forceDelete();
        return response()->json(['message' => 'TaxiQeuee permanently deleted']);
    }

}
