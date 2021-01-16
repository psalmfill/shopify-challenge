<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AddImageRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Folder;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ImageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::when(request()->query('search'), function ($query) {
            return $query->where('caption', "like", "%" . request()->query('search') . "%");
        })->paginate();
        return $this->response($images);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddImageRequest $request, Image $image)
    {
        try {
            if ($request->images and is_array($request->images)) {
              
                // FOR MULTIPLE IMAGE UPLOAD CREATE FOLDER TO UPLOAD TO USING THE CAPTION IF NO FOLDER IS SPECIFY

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
                $images = [];

                foreach ($request->images as $img) {
                    $name = $img->getClientOriginalName();
                    $data['path'] = $img->store('public/images');
                    $data['caption'] = $name;
                    $data['folder_id'] = $folder->id;
                    $createdImage = $image->create(array_merge($data, ['user_id' => auth()->id()]));
                    array_push($images, $createdImage);
                }
                return $this->response($images, 'Images uploaded', 'success', Response::HTTP_CREATED);
            } else {
                
                $data['path'] = $request->file('image')->store('public/images');
                $data['caption'] = $request->caption;
                $data['folder_id'] = $request->folder_id;
                $img = $image->create(array_merge($data, ['user_id' => auth()->id()]));

                return $this->response($img, 'Image created', 'success', Response::HTTP_CREATED);
            }
        } catch (Exception $e) {
            return $this->response(null, 'Could not create image', 'fail', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(Image $image)
    {
        return $this->response($image);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        $image->delete();
        return response()->json()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
