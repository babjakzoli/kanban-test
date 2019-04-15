<?php

namespace App\Http\Controllers;

use App\Models\Lists;
use Validator;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Lists::all();

        return view('lists')->with([
            'lists' => $lists
        ]);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validator' => $validator->errors()
            ], 400);
        }

        return json_encode(Lists::create([
            'name' => $request->get('name')
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Lists::findOrFail($id);
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
        $list = Lists::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validator' => $validator->errors()
            ], 400);
        }

        $list->name = $request->get('name');
        $list->save();

        return json_encode($list->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $list = Lists::withCount('tasks')->findOrFail($id);

        if ($list->tasks_count === 0) {
            $list->delete();
            return $id;
        }

        return response()->json(['error' => 'The list has tasks..'], 403);
    }
}
