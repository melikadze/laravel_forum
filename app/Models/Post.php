<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::saving(fn(self $post) => $post->fill(['html' => str($post->body)->markdown()]) );
    }

    /**
     * Get the user that owns the Post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the comments for the Post
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function title(): Attribute
    {
        return Attribute::set(fn($value) => Str::title($value));
    }

    public function body(): Attribute
    {
        return Attribute::set(fn ($value) => [
            'body' => $value,
            'html' => str($value)->markdown()
        ]);
    }

    public function showRoute(array $parameters = [])
    {
        return route('posts.show', [$this, Str::slug($this->title), ...$parameters]);
    }
}
