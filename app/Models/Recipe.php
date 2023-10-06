<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ["titre", "description", "ingredient_id"];

    public function classe()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
