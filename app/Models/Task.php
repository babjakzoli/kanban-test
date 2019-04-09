<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'list_id', 'order', 'user_id', 'created_user_id', 'due_date'];

    public function assignUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function createdUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_user_id');
    }
}
