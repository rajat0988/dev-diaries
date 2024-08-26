<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Reply;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    public function store(Request $request, $questionId)
    {
        $request->validate([
            'Content' => 'required|string',
        ]);

        $user = Auth::user();;
        $question = Question::findOrFail($questionId);

        $reply = new Reply;
        $reply->question_id = $questionId;
        $reply->UserName = $user->name;
        $reply->user_id = Auth::id();
        $reply->EmailId = $user->email;
        $reply->Content = $request->input('Content');
        $reply->Upvotes = 0;
        $reply->save();

        $question->update([
            'Answered' => true,
        ]);

        return redirect()->route('questions.show', $questionId)->with('success', 'Reply posted successfully!');
    }

    public function upvote($id)
    {
        $reply = Reply::findOrFail($id);
        $user = Auth::user();
    
        $existingVote = $reply->votes()->where('user_id', $user->id)->first();
    
        if ($existingVote) {
            if ($existingVote->vote_type == 1) {
                return redirect()->back()->with('error', 'You have already upvoted this reply.');
            } else {
                $existingVote->update(['vote_type' => 1]);
                $reply->increment('Upvotes', 2);
            }
        } else {
            $reply->votes()->create([
                'user_id' => $user->id,
                'vote_type' => 1
            ]);
            $reply->upvote();
        }
    
        return redirect()->back()->with('success', 'Reply upvoted successfully!');
    }
    
    public function downvote($id)
    {
        $reply = Reply::findOrFail($id);
        $user = Auth::user();
    
        $existingVote = $reply->votes()->where('user_id', $user->id)->first();
    
        if ($existingVote) {
            if ($existingVote->vote_type == 0) {
                return redirect()->back()->with('error', 'You have already downvoted this reply.');
            } else {
                $existingVote->update(['vote_type' => 0]);
                $reply->decrement('Upvotes', 2);
            }
        } else {
            $reply->votes()->create([
                'user_id' => $user->id,
                'vote_type' => 0
            ]);
            $reply->downvote();
        }
    
        return redirect()->back()->with('success', 'Reply downvoted successfully!');
    }

    public function destroy($id)
    {
        $reply = Reply::findOrFail($id);
        $reply->delete();

        return redirect()->back()->with('success', 'Reply deleted successfully!');
    }
}
