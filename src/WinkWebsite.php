<?php

namespace Wink;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $domain
 * @property string $name
 * @property CarbonInterface $updated_at
 * @property CarbonInterface $created_at
 * @property array<mixed>|null $meta
 * @property-read Collection<WinkPost> $posts
 * @property-read Collection<WinkTags> $tags
 * @property-read Collection<WinkPage> $pages
 */
class WinkWebsite extends AbstractWinkModel
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wink_websites';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be casted.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * The posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(WinkPost::class, 'website_id');
    }

    /**
     * The tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany(WinkTag::class, 'website_id');
    }

    /**
     * The pages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(WinkPage::class, 'website_id');
    }



    // /**
    //  * The "booting" method of the model.
    //  *
    //  * @return void
    //  */
    // protected static function boot()
    // {
    //     parent::boot();

    //     // static::deleting(function ($item) {
    //     //     $item->posts()->detach();
    //     // });
    // }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
