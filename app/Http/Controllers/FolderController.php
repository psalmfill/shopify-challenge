<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, Folder $folder)
    {
        try {
            $request->validate([
                'name' => ['required', Rule::unique('folders')->where('user_id', auth()->id())]
            ]);
            $folder->create(array_merge($request->all(), ['user_id' => auth()->id()]));
            return redirect()->back();
        } catch (Exception $e) {
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function show(Folder $folder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function edit(Folder $folder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folder $folder)
    {
        try {
            $folder->update(['name' => $request->name]);
            return redirect()->back()->with('message','Folder renamed.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Folder Rename failed');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
        try {
            $folder->delete();
            return redirect()->back();
        } catch (Exception $e) {
            dd($e);
        }
    }
}
