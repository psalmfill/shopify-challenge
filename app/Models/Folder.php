<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['name', 'user_id', 'parent_id'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($folder){
            $folder->folders()->delete();
            $folder->images()->delete();
        });
    }

    public function toArray()
    {
       
        return array_merge(parent::toArray() ,['total_images' => $this->images()->count(), 'total_folders'=> $this->folders()->count()]  );
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function folders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function getTotalFilesAttribute()
    {
        return $this->folders()->count() + $this->images()->count();
    }
}
