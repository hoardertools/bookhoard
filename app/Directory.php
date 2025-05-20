<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Directory extends Model
{


    protected $fillable = [
        'path',
        'library_id',
        'regex',
        'directory_id'
    ];

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Directory::class, 'directory_id');
    }

    public function children()
    {
        return $this->hasMany(Directory::class, 'directory_id');
    }

    public function removeAllChildren(){
        //Remove children directories to multiple levels
        foreach($this->children as $child){
            $child->removeAllChildren();
            $child->books()->delete();
            $child->delete();

        }
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
