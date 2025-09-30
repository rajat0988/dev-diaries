<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reply;

class AdminController extends Controller
{
    public function reportedItems()
    {
        // Use pagination to avoid loading all reported items at once
        $reportedQuestions = Question::select('id', 'UserName', 'user_id', 'Title', 'Content', 'Upvotes', 'reported', 'created_at')
            ->where('reported', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $reportedReplies = Reply::select('id', 'question_id', 'UserName', 'user_id', 'Content', 'Upvotes', 'reported', 'created_at')
            ->where('reported', true)
            ->with(['question:id,Title'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.reported', compact('reportedQuestions', 'reportedReplies'));
    }
}
