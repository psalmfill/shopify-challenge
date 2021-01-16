<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $folderName = request()->query('folder');
        $search = request()->query('search');
        if ($folderName) {
            $folder = Folder::where('name', $folderName)
                ->where('user_id', auth()->id())->first();

            $folders = Folder::where('user_id', auth()->id())->where('parent_id', $folder->id)
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%$search%");
                })->get();

            $images = Image::where('user_id', auth()->id())->where('folder_id', $folder->id)
                ->when($search, function ($query, $search) {
                    return $query->where('caption', 'like', "%$search%");
                })->get();

            $files = collect($folders)->merge($images);
            return view('home', compact('files', 'folder'));
        } else {
            $folders = Folder::where('user_id', auth()->id())
                ->when(!$search, function ($query) {
                    return $query->where('parent_id', null);
                })->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%$search%");
                })->get();

            $images = Image::where('user_id', auth()->id())
                ->when(!$search, function ($query) {
                    return $query->where('folder_id', null);
                })
                ->when($search, function ($query, $search) {
                    return $query->where('caption', 'like', "%$search%");
                })->get();

            $files = collect($folders)->merge($images);
            return view('home', compact('files'));
        }
    }

    public function configurations()
    {
        return view('configurations', ['user' => auth()->user()]);
    }

    public function resetApiKey()
    {
        $user = auth()->user();
        $user->api_public_key = 'IR-PKEY-' . Str::random();
        $user->api_secret_key = 'IR-SKEY-' . Str::random();
        $user->save();
        return redirect()->back()->with('message', 'API Keys reset successful');
    }
}
