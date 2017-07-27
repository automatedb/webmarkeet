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

    public static $TITLE = 'title';

    // Properties
    public static $SLUG = 'slug';

    public static $CONTENT = 'content';

    public static $STATUS = 'status';

    public static $TYPE = 'type';

    public static $THUMBNAIL = 'thumbnail';

    public static $USER_ID = 'user_id';

    protected $table = 'contents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'content', 'status', 'type', 'thumbnail', 'user_id'];

}
