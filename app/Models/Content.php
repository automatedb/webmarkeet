<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    // Status
    public const PUBLISHED = 'PUBLISHED';

    public const DRAFT = 'DRAFT';

    // Types
    public const CONTENT = 'CONTENT';

    public const TUTORIAL = 'TUTORIAL';

    // Properties
    public static $TITLE = 'title';

    public static $SLUG = 'slug';

    public static $CONTENT = 'content';

    public static $STATUS = 'status';

    public static $TYPE = 'type';

    public static $VIDEO_ID = 'video_id';

    public static $THUMBNAIL = 'thumbnail';

    public static $POSTED_AT = 'posted_at';

    public static $USER_ID = 'user_id';

    protected $table = 'contents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'content', 'status', 'type', 'video_id', 'thumbnail', 'posted_at', 'user_id'];

    public function sources() {
        return $this->belongsToMany(\App\Models\Source::class, 'contains_sources');
    }
}
