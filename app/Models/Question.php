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
        'Upvotes',
        'Tags',
        'Answered'
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

    //Helper functions:
    function sanitizeContent($content)
    {
        $replacements = [
            '<' => '&lt;',
            '>' => '&gt;',
            '{' => '&lcub;',  // Using HTML entity for left curly brace
            '}' => '&rcub;',  // Using HTML entity for right curly brace
        ];

        return strtr($content, $replacements);
    }

    function desanitizeContent($content)
    {
        $replacements = [
            '&lt;' => '<',
            '&gt;' => '>',
            '&lcub;' => '{',
            '&rcub;' => '}',
        ];

        return strtr($content, $replacements);
    }


    // Mutator to sanitize content before saving
    public function setContentAttribute($value)
    {
        $this->attributes['Content'] = self::sanitizeContent($value);
    }

    // Accessor to desanitize content when retrieving
    public function getContentAttribute($value)
    {
        return self::desanitizeContent($value);
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
