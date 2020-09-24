<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model {

    protected $table = 'recipe';
    protected $fillable = ['title', 'telegraph_link', 'status', 'created_by_admin', 'created_by_user'];

}