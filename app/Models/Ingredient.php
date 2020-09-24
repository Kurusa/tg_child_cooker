<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model {

    protected $table = 'ingredient';
    protected $fillable = ['title'];

}