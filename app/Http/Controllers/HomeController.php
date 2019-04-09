<?php

namespace App\Http\Controllers;

use App\Models\Lists;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $lists = Lists::with([
            'tasks' => function($task) {
                $task->with(['assignUser', 'createdUser']);
            }
        ])->get();
        $users = User::get();

        return view('home')->with([
            'lists' => $lists,
            'users' => $users
        ]);
    }
}
