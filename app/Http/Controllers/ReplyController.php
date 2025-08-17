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
                // User has already upvoted, so remove the vote
                $existingVote->delete();
                $reply->decrement('Upvotes'); // Decrement by 1
                $message = 'Upvote removed successfully!';
                $userVote = null;
            } else {
                // User has downvoted, switch to upvote
                $existingVote->update(['vote_type' => 1]);
                $reply->increment('Upvotes', 2);
                $message = 'Reply upvoted successfully!';
                $userVote = 1;
            }
        } else {
            $reply->votes()->create([
                'user_id' => $user->id,
                'vote_type' => 1
            ]);
            $reply->upvote();
            $message = 'Reply upvoted successfully!';
            $userVote = 1;
        }
    
        // Refresh the reply to get updated upvotes count
        $reply->refresh();
    
        // Check if it's an AJAX request
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => $message,
                'upvotes' => $reply->Upvotes,
                'userVote' => $userVote
            ]);
        }
    
        return redirect()->back()->with('success', $message);
    }
    
    public function downvote($id)
    {
        $reply = Reply::findOrFail($id);
        $user = Auth::user();
    
        $existingVote = $reply->votes()->where('user_id', $user->id)->first();
    
        if ($existingVote) {
            if ($existingVote->vote_type == 0) {
                // User has already downvoted, so remove the vote
                $existingVote->delete();
                $reply->increment('Upvotes'); // Increment by 1 (removing downvote)
                $message = 'Downvote removed successfully!';
                $userVote = null;
            } else {
                // User has upvoted, switch to downvote
                $existingVote->update(['vote_type' => 0]);
                $reply->decrement('Upvotes', 2);
                $message = 'Reply downvoted successfully!';
                $userVote = 0;
            }
        } else {
            $reply->votes()->create([
                'user_id' => $user->id,
                'vote_type' => 0
            ]);
            $reply->downvote();
            $message = 'Reply downvoted successfully!';
            $userVote = 0;
        }
    
        // Refresh the reply to get updated upvotes count
        $reply->refresh();
    
        // Check if it's an AJAX request
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => $message,
                'upvotes' => $reply->Upvotes,
                'userVote' => $userVote
            ]);
        }
    
        return redirect()->back()->with('success', $message);
    }

    public function destroy($id)
    {
        $reply = Reply::findOrFail($id);
        $reply->delete();

        return redirect()->back()->with('success', 'Reply deleted successfully!');
    }
}
