<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\DB;


class QuestionController extends Controller
{
    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'UserName' => 'required|string|max:255',
        'EmailId' => 'required|email|max:255',
        'Title' => 'required|string|max:255',
        'Content' => 'required|string',
        'Upvotes' => 'nullable|integer|min:0',
        'Tags' => 'nullable|array',
        'Answered' => 'nullable|boolean',
    ]);

    $post = new Question;
    $post->UserName = $request->input('UserName');
    $post->EmailId = $request->input('EmailId');
    $post->Title = $request->input('Title');
    $post->Content = $request->input('Content');
    $post->Upvotes = 0;
    $post->Tags = json_encode($request->input('Tags', []));
    $post->Answered = false;
    $post->save();

    return redirect()->route('questions.index')->with('success', 'Post created successfully!');
    }

    public function index()
    {
        $all_questions = Question::orderBy('created_at', 'desc')->get();
        return view('questions.index', compact('all_questions'));
    }

    public function show($id)
    {
        $question = Question::findOrFail($id);
        return view('questions.show', compact('question'));
    }

    public function upvote($id)
    {
        $question = Question::findOrFail($id);
        $question->upvote();
        return redirect()->back()->with('success', 'Question upvoted successfully!');
    }

    public function downvote($id)
    {
        $question = Question::findOrFail($id);
        $question->downvote();
        return redirect()->back()->with('success', 'Question downvoted successfully!');
    }

    public function filterByTag(Request $request)
    {
        $selectedTags = $request->input('tags');

        if ($selectedTags) {
            // Filter questions by selected tags
            $filtered_questions = Question::where(function($query) use ($selectedTags) {
                foreach ($selectedTags as $tag) {
                    $query->orWhereJsonContains('Tags', $tag);
                }
            })->orderBy('created_at', 'desc')->get();
        } else {
            $filtered_questions = [];
        }

        $all_questions = Question::orderBy('created_at', 'desc')->get();

        return view('questions.index', compact('filtered_questions', 'all_questions', 'selectedTags'));
    }
}