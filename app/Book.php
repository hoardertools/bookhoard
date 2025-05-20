<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }

    public function directory(): BelongsTo
    {
        return $this->belongsTo(Directory::class);
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(Metadata::class);
    }
    protected function casts(): array
    {
        return [
            'metadata_set' => 'boolean',
        ];
    }

    public function getEpisodeName()
    {
        if(strlen($this->title) > 0){
            return $this->title;
        }else{
            return $this->filename;
        }

     }

}
