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
        return $this->belongsTo(Question::class);
    }
}
