<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reply;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function reportQuestion($id)
    {
        $question = Question::findOrFail($id);
        $question->reported = true;
        $question->save();

        return redirect()->back()->with('status', 'Question reported successfully!');
    }

    public function reportReply($id)
    {
        $reply = Reply::findOrFail($id);
        $reply->reported = true;
        $reply->save();

        return redirect()->back()->with('status', 'Reply reported successfully!');
    }
}
