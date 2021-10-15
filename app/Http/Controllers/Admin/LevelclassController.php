<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Levelclass;
use Illuminate\Http\Request;
use Str;

class LevelclassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $levelclasses = Levelclass::all();

        return view('pages.admin.levelclass.index',compact(
            'levelclasses'
        ));
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
        $this->validate($request,[
            'name' => 'required|numeric|unique:levelclasses,name'
        ]);

        $data = $request->all();
        $data['slug'] = Str::random(10);

        $levelclass = Levelclass::create($data);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Levelclass $levelclass)
    {
        return response()->json($levelclass);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Levelclass $levelclass)
    {
        $this->validate($request,[
            'name' => 'required|numeric|unique:levelclasses,name,'.$levelclass->id
        ]);

        $levelclass->update([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Levelclass $levelclass)
    {
        $levelclass->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
