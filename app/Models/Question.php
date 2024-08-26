<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
