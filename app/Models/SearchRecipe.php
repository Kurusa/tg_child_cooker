<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchRecipe extends Model {

    protected $table = 'search_recipe';
    protected $fillable = ['user_id', 'ingredient_id'];

}