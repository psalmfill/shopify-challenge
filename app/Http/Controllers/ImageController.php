<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ImageController extends Controller
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
    public function store(Request $request, Image $image)
    {
        try {
            if ($request->images and is_array($request->images)) {
                $request->validate([
                    'images' => ['required'],
                    'caption' => ['required'],
                    'folder_id' => 'nullable|exists:folders,id'
                ]);

                // FOR MULTIPLE IMAGE UPLOAD CREATE FOLDER TO UPLOAD TO USING THE CAPTION IF NO FOLDER IS SPECIFY
                // dd(Storage::files('public/images'));
                $folder = Folder::where([
                    ['name', $request->caption],
                    ['user_id', auth()->user()],
                    ['parent_id', $request->folder_id]
                ])->first();
                if (!$folder) {
                    $folder = auth()->user()->folders()->create([
                        'name' => $request->caption,
                        'parent_id' => $request->folder_id,
                    ]);
                }



                // upload images to folder
                foreach ($request->images as $img) {
                    $name = $img->getClientOriginalName();
                    $data['path'] = $img->store('public/images');
                    $data['caption'] = $name;
                    $data['folder_id'] = $folder->id;
                    $image->create(array_merge($data, ['user_id' => auth()->id()]));
                }
            } else {
                $request->validate([
                    'image' => ['required'],
                    'caption' => ['required',  Rule::unique('images')->where('user_id', auth()->id())],
                    'folder_id' => 'nullable|exists:folders,id'
                ]);
                $data['path'] = $request->file('image')->store('public/images');
                $data['caption'] = $request->caption;
                $data['folder_id'] = $request->folder_id;
                $image->create(array_merge($data, ['user_id' => auth()->id()]));
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Image Deletion failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        try {
            $count = Image::where('caption', $request->caption)->where('user_id', auth()->id())->where('id', '!=', $image->id)->count();
            $caption = $count ? $request->caption . ' ' . $count : $request->caption;
            $image->update(['caption' => $caption]);
            return redirect()->back()->with('message', 'Image caption updated');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Image caption update failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        try {
            $image->delete();
            return redirect()->back()->with('message', 'Image Deleted');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Image Deletion failed');
        }
    }
}
