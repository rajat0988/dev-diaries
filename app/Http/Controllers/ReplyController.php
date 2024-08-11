<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Reply;

use Illuminate\Support\Facades\DB;



class ReplyController extends Controller
{
    public function store(Request $request, $questionId)
    {
        $request->validate([
            'UserName' => 'required|string|max:255',
            'EmailId' => 'required|email|max:255',
            'Content' => 'required|string',
        ]);

        $question = Question::findOrFail($questionId);

        $question->replies()->create([
            'UserName' => $request->input('UserName'),
            'EmailId' => $request->input('EmailId'),
            'Content' => $request->input('Content'),
        ]);

        $question->update([
            'Answered' => true,
        ]);

        return redirect()->route('questions.show', $questionId)->with('success', 'Reply added successfully!');
    }

    public function upvote($id)
    {
        $reply = Reply::findOrFail($id);
        $reply->upvote();
        return redirect()->back()->with('success', 'Reply upvoted successfully!');
    }

    public function downvote($id)
    {
        $reply = Reply::findOrFail($id);
        $reply->downvote();
        return redirect()->back()->with('success', 'Reply downvoted successfully!');
    }

    public function resetAutoIncrement()
    {
        // Delete all records in the replies table
        DB::table('replies')->delete();

        // Reset the auto-increment counter
        DB::statement('DELETE FROM sqlite_sequence WHERE name="replies";');

        return redirect()->back()->with('success', 'Auto-increment counter reset successfully!');
    }

}
