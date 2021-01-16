<?php

namespace App\Http\Controllers\Api;

use App\Models\Folder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class FolderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Folder::when(request()->query('search'), function($query){
            return $query->where('name',"like", "%".request()->query('search')."%");
        })->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,  Folder $folder)
    {
        $request->validate([
            'name' => ['required', Rule::unique('folders')->where('user_id', auth()->id())]
        ]);
        try {
            $folder = $folder->create(array_merge($request->all(), ['user_id' => auth()->id()]));

            return $this->response($folder, 'Folder created', 'success', Response::HTTP_CREATED);
        } catch (Exception $e) {

            return $this->response(null, 'Could not create folder', 'fail', Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $search = request()->query('search');
        $folders = Folder::where('user_id', auth()->id())->where('parent_id', $folder->id)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%");
            })->get();

        $images = Image::where('user_id', auth()->id())->where('folder_id', $folder->id)
            ->when($search, function ($query, $search) {
                return $query->where('caption', 'like', "%$search%");
            })->get();

        return $this->response([
            'detail' => $folder,
            'data' => [
                'folders' => $folders,
                'images' => $images,
            ]
        ]);
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

            return $this->response(null, 'folder renamed successfully', 'success');
        } catch (Exception $e) {

            return $this->response(null, "folder rename failed: " . $e->getMessage(), 'failed');
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
        $folder->delete();
        return $this->response(null, 'folder deleted successfully', 'success', Response::HTTP_CREATED);
    }
}
