<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lists extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'list_id')->orderBy('order', 'asc');
    }
}
