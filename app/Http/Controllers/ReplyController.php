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
        $reply->upvote();
        return redirect()->back()->with('success', 'Reply upvoted successfully!');
    }

    public function downvote($id)
    {
        $reply = Reply::findOrFail($id);
        $reply->downvote();
        return redirect()->back()->with('success', 'Reply downvoted successfully!');
    }

    public function destroy($id)
    {
        $reply = Reply::findOrFail($id);
        $reply->delete();

        return redirect()->back()->with('success', 'Reply deleted successfully!');
    }


    //Do not uncomment this under any circumstance. 
    // DB::statement('DELETE FROM sqlite_sequence WHERE name="replies";');
}
