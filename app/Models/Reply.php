<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'question_id',
        'UserName',
        'EmailId',
        'Content',
        'Upvotes'
    ];

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


    // Define the inverse relationship with the Question model
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
