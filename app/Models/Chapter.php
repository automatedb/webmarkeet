<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    // Properties
    public const TITLE = 'title';

    public const CONTENT = 'content';

    public const CONTENT_ID = 'content_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content', 'content_id'];

    public function sources() {
        return $this->belongsToMany(\App\Models\Source::class, 'contains_sources');
    }
}
