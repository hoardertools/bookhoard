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

    public function getSeriesAttribute()
    {
        return $this->metadata()->where("key", "=", "series")->first()->value;

    }

    public function getIssueAttribute()
    {
        return $this->metadata()->where("key", "=", "issue")->first()->value;

    }

    public function getTitleAttribute()
    {
        return $this->metadata()->where("key", "=", "title")->first()->value;

    }

    public function otherMeta()
    {
        return $this->metadata()->whereNotIn("key", ["series", "issue", "title"])->get();
    }
    public function image(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

    public function renderImage(){


        $image = $this->image()->first();

        if($image->base64) {

             return '<img src="data:image/png;base64,' . $image->base64 . '" alt="' . $this->title . '" class="img-fluid" style="max-width: 100%;">';
        }

        return "";

    }

}
