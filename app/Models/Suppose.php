<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suppose extends Model
{

    protected $table = 'suppose';
    protected $fillable = ['user_id', 'text', 'image', 'moderated'];

}