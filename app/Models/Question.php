<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Question extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'UserName',
        'EmailId',
        'Title',
        'Content',
        'image_url',
        'Upvotes',
        'Tags',
        'Answered'
    ];

    // Define which attributes should be cast
    protected $casts = [
        'Tags' => 'array', // Automatically cast JSON to array
        'Answered' => 'boolean',
    ];

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function upvote()
    {
        $this->increment('Upvotes');
    }

    public function downvote()
    {
        $this->decrement('Upvotes');
    }

    // Mutator to normalize tags before saving
    public function setTagsAttribute($value)
    {
        if (is_array($value)) {
            $tagArray = $value;
        } elseif (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $tagArray = $decoded;
            } else {
                // Handle comma-separated string
                $tagArray = explode(',', $value);
            }
        } else {
            $tagArray = [];
        }

        $tagArray = array_map(function ($tag) {
            return strtolower(trim((string)$tag));
        }, $tagArray);

        $tagArray = array_values(array_unique(array_filter($tagArray)));

        $this->attributes['Tags'] = json_encode($tagArray);
    }

    // Define the relationship with the Reply model
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
