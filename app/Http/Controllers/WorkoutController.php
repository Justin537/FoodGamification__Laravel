<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\workout;

class WorkoutController extends Controller
{
    public function index()
    {
        return response()->json(workout::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id' => 'required|integer',
            'name' => 'required|string',
            'calories' => 'required|integer',
            'date' => 'nullable|date',
        ]);

        $item = workout::create($data);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = workout::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = workout::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'calories' => 'sometimes|required|integer',
            'date' => 'sometimes|date',
        ]);

        $item->update($data);
        return response()->json($item);
    }

    public function destroy($id)
    {
        workout::destroy($id);
        return response()->json(null, 204);
    }
}
