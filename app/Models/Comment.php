<?php

namespace App\Models;

use App\Models\Concerns\ConvertsMarkdownToHtml;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    use ConvertsMarkdownToHtml;


    /**
     * Get the user that owns the Comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post that owns the Comment
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function likes() : MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
