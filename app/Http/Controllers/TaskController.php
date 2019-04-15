<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    /**
     * Save order of tasks and get into list
     *
     * @param Request $request
     */
    public function saveOrder(Request $request)
    {
        if ($request->has('data')) {
            $i = 1;
            foreach (collect($request->get('data'))->pluck('list_id', 'task_id')->toArray() as $task_id => $list_id) {
                $task = Task::find($task_id);
                $task->order = $i++;
                $task->list_id = $list_id;
                $task->save();
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return ()
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task = [];

        foreach ($request->get('data') as $key => $data) {
            $task[$data['name']] = $data['value'];

            if ($data['name'] == 'due_date' && !is_null($data['value'])) {
                $task['due_date'] = date('Y-m-d', strtotime($data['value']));
            }
        }

        $validator = Validator::make($task, [
            'title' => 'required',
            'due_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validator' => $validator->errors()
            ], 400);
        }

        $task['created_user_id'] = Auth::id();
        $task['order'] = Task::max('order') + 1;

        $create = Task::create($task);

        return json_encode($create->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Task::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $task = Task::findOrFail($id);
        $tmp = [];

        foreach ($request->get('data') as $key => $data) {
            $task->{$data['name']} = $data['value'];
            $tmp[$data['name']] = $data['value'];

            if ($data['name'] == 'due_date' && !is_null($data['value'])) {
                $task->due_date = date('Y-m-d', strtotime($data['value']));
                $tmp['due_date'] = date('Y-m-d', strtotime($data['value']));
            }
        }

        $validator = Validator::make($tmp, [
            'title' => 'required',
            'due_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validator' => $validator->errors()
            ], 400);
        }

        $task->save();

        return json_encode($task->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::destroy($id);
        return $id;
    }
}
