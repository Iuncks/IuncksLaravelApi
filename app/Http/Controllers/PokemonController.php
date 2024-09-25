<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pokemon;


class PokemonController extends Controller
{
    public function index()
    {
        return Pokemon::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'level' => 'required|integer',
        ]);

        return Pokemon::create($validated);
    }

    public function show($id)
    {
        return Pokemon::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $pokemon = Pokemon::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string',
            'level' => 'integer',
        ]);

        $pokemon->update($validated);
        return $pokemon;
    }

    public function destroy($id)
    {
        $pokemon = Pokemon::findOrFail($id);
        $pokemon->delete();
        return response(null, 204);
    }
}


