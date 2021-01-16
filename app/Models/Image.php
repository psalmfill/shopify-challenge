<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::deleting(function($image){
            // remove image from storage]
            Storage::delete($image->path);
        });
    }
    protected $fillable = [
        'path','caption','folder_id','user_id'
    ];

    protected $hidden = ['path'];

    public function toArray()
    {
       
        return array_merge( ['url' => $this->url, 'folder'=> $this->folder], parent::toArray() );
    }
    public function getUrlAttribute()
    {
        return asset(Storage::url($this->path));
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
