<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IngredientController extends Controller
{
    // public function getIngredient(Request $request) {
    //     $data = Ingredient::where('titre', 'LIKE', "%" . $request->searchItem . '%')->paginate(10);
    //     return response()->json($data);
    // }    
}
