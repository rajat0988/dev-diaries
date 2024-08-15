<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class QuestionController extends Controller
{
    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Title' => 'required|string|max:255',
            'Content' => 'required|string',
            'Tags' => 'nullable|array',
        ]);

        $user = Auth::user();

        $post = new Question;
        $post->UserName = $user->name;
        $post->EmailId = $user->email;
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
        $all_questions = Question::orderBy('created_at', 'desc')->paginate(5);
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
            // Filter questions by selected tags with pagination
            $filtered_questions = Question::where(function($query) use ($selectedTags) {
                foreach ($selectedTags as $tag) {
                    $query->orWhereJsonContains('Tags', $tag);
                }
            })->orderBy('created_at', 'desc')->paginate(5);
        } else {
            $filtered_questions = collect(); // Empty collection
        }
        
        $all_questions = Question::orderBy('created_at', 'desc')->paginate(5);
        
        return view('questions.index', compact('filtered_questions', 'all_questions', 'selectedTags'));
    }
    
}