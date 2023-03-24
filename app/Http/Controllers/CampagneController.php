<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campagne;

class CampagneController extends Controller
{
    public function index()
    {
        $campagnes = Campagne::all();
        return response()->json($campagnes);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campagne = new Campagne;
        $campagne->nom = $request->nom;
        $campagne->description = $request->description;
        $campagne->save();

        return response()->json($campagne);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campagne = Campagne::findOrFail($id);
        return response()->json($campagne);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campagne = Campagne::findOrFail($id);
        $campagne->nom = $request->nom;
        $campagne->description = $request->description;
        $campagne->update();
        return response()->json($campagne);
        
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campagne = Campagne::findOrFail($id);
        $campagne->delete();
        return response()->json(['message' => 'Campagne supprimée avec succès ']);
    }
}
