<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{

    public function index() {
        return view('index');
    }
 
    public function fetchAll() {
        $recipe = Recipe::all();
        $output = '';
        if ($recipe->count() > 0) {
            $output .= '<table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Ingrédient</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($recipe as $rs) {
                $output .= '<tr>
                <td>' . $rs->id . '</td>
                <td>' . $rs->titre .'</td>
                <td>' . $rs->description . '</td>
                <td>' . $rs->ingredient_id . '</td>
                <td>
                  <a href="#" id="' . $rs->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editRecipeModal"><i class="bi-pencil-square h4"></i></a>
                  <a href="#" id="' . $rs->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record in the database!</h1>';
        }
    }
 
    // insert a new recipe ajax request
    public function store(Request $request) {
 // Validez les données du formulaire ici
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredient' => 'required|exists:ingredients,id',
        ]);

        $recipe = new Recipe();
        $recipe->titre = $request->input('titre');
        $recipe->description = $request->input('description');
        $recipe->ingredient_id = $request->input('ingredient');
        $recipe->save();

        return response()->json(['status' => 200]);
    }
 
    // edit an recipe ajax request
    public function edit(Request $request) {
        $id = $request->id;
        $emp = Recipe::find($id);
        return response()->json($emp->toArray());
    }
 
    public function update(Request $request) {
        $emp = Recipe::find($request->emp_id);
 
        $empData = ['titre' => $request->titre, 'description' => $request->description, 'ingredient' => $request->ingredient];
 
        $emp->update($empData);
        return response()->json([
            'status' => 200,
        ]);
    }
 
    // delete an recipe ajax request
    public function delete(Request $request) {
        $id = $request->id;
        $recipe = Recipe::find($id);
        if ($recipe) {
            $recipe->delete();
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 404]);
        }
    }
    

    public function getIngredient(Request $request) {
        $data = Ingredient::where('titre', 'LIKE', "%" . $request->searchItem . '%')->paginate(10);
        return response()->json($data);
    }  
}