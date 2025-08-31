<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\calories;
class CalorieController extends Controller
{
    public function index()
    {
        return response()->json(calories::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_account' => 'nullable|integer',
            'category' => 'required|string',
            'name' => 'required|string',
            'calories' => 'required|integer',
            'date' => 'nullable|date',
        ]);

        $item = calories::create($data);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = calories::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = calories::findOrFail($id);
        $data = $request->validate([
            'category' => 'sometimes|required|string',
            'name' => 'sometimes|required|string',
            'calories' => 'sometimes|required|integer',
            'date' => 'sometimes|date',
        ]);

        $item->update($data);
        return response()->json($item);
    }

    public function destroy($id)
    {
        calories::destroy($id);
        return response()->json(null, 204);
    }
    
    public function checkCalories(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $image = $request->file('image');
        $tempPath = $image->getPathname();

        $response = Http::attach(
            'image',
            file_get_contents($tempPath),
            $image->getClientOriginalName()
        )->post('http://127.0.0.1:5000/predict');

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Failed to get response from AI model'
            ], 500);
        }
    }
}

