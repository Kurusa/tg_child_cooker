<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $table = 'user';
    protected $fillable = ['is_blocked', 'user_name', 'chat_id', 'phone_number', 'status', 'confirm_code', 'crm_user_id'];

}