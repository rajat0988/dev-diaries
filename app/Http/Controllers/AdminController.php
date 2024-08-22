<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reply;

class AdminController extends Controller
{
    public function reportedItems()
    {
        $reportedQuestions = Question::where('reported', true)->get();
        $reportedReplies = Reply::where('reported', true)->get();

        return view('admin.reported', compact('reportedQuestions', 'reportedReplies'));
    }
}
