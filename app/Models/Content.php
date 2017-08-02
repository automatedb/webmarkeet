<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    // Status
    public const PUBLISHED = 'PUBLISHED';

    public const DRAFT = 'DRAFT';

    public const CONTENT = 'CONTENT';

    // Types
    public const TUTORIAL = 'TUTORIAL';

    // Properties
    public static $TITLE = 'title';

    public static $SLUG = 'slug';

    public static $CONTENT = 'content';

    public static $STATUS = 'status';

    public static $TYPE = 'type';

    public static $THUMBNAIL = 'thumbnail';

    public static $POSTED_AT = 'posted_at';

    public static $USER_ID = 'user_id';

    protected $table = 'contents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'content', 'status', 'type', 'thumbnail', 'posted_at', 'user_id'];

    public function sources() {
        return $this->belongsToMany(\App\Models\Source::class, 'contains_sources');
    }
}
