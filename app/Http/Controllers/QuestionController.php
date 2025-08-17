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
            'custom_tags' => 'nullable|string',
        ]);

        $user = Auth::user();
        $tags = $request->input('Tags', []);

        $customTags = $request->input('custom_tags');
        if ($customTags) {
            $customTagsArray = array_map('trim', explode(',', $customTags));
            $tags = array_merge($tags, $customTagsArray);
        }

        $tags = array_unique($tags);

        $post = new Question;
        $post->UserName = $user->name;
        $post->user_id = Auth::id();
        $post->EmailId = $user->email;
        $post->Title = $request->input('Title');
        $post->Content = $request->input('Content');
        $post->Upvotes = 0;
        $post->Tags = json_encode($tags);
        $post->Answered = false;
        $post->save();

        return redirect()->route('questions.index')->with('success', 'Post created successfully!');
    }

    public function index()
    {
        $tagCounts = DB::table('questions')
            ->select(DB::raw('json_extract(Tags, "$[*]") as tag'))
            ->pluck('tag')
            ->flatten()
            ->map(fn($tag) => json_decode($tag, true))
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc();

        $mostUsedTags = $tagCounts->keys()->take(5);

        $all_questions = Question::orderBy('created_at', 'desc')->paginate(5);

        return view('questions.index', compact('all_questions', 'mostUsedTags'));
    }

    public function show($id)
    {
        $question = Question::with('replies')->findOrFail($id);
        $recent_questions = Question::orderBy('created_at', 'desc')->paginate(4);
        
        // Get user's vote status for the question
        $userVote = null;
        if (Auth::check()) {
            $userVote = $question->votes()->where('user_id', Auth::id())->first();
        }
        
        // Get user's vote status for each reply
        $replyVotes = [];
        if (Auth::check()) {
            foreach ($question->replies as $reply) {
                $vote = $reply->votes()->where('user_id', Auth::id())->first();
                if ($vote) {
                    $replyVotes[$reply->id] = $vote->vote_type;
                }
            }
        }
        
        return view('questions.show', compact('question', 'recent_questions', 'userVote', 'replyVotes'));
    }

    public function upvote($id)
    {
        $question = Question::findOrFail($id);
        $user = Auth::user();

        $existingVote = $question->votes()->where('user_id', $user->id)->first();

        if ($existingVote) {
            if ($existingVote->vote_type == 1) {
                // User has already upvoted, so remove the vote
                $existingVote->delete();
                $question->decrement('Upvotes'); // Decrement by 1
                $message = 'Upvote removed successfully!';
                $userVote = null;
            } else {
                // User has downvoted, switch to upvote
                $existingVote->update(['vote_type' => 1]);
                $question->increment('Upvotes', 2); // Adjust the count accordingly
                $message = 'Question upvoted successfully!';
                $userVote = 1;
            }
        } else {
            $question->votes()->create([
                'user_id' => $user->id,
                'vote_type' => 1
            ]);
            $question->upvote();
            $message = 'Question upvoted successfully!';
            $userVote = 1;
        }

        // Refresh the question to get updated upvotes count
        $question->refresh();

        // Check if it's an AJAX request
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => $message,
                'upvotes' => $question->Upvotes,
                'userVote' => $userVote
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function downvote($id)
    {
        $question = Question::findOrFail($id);
        $user = Auth::user();

        $existingVote = $question->votes()->where('user_id', $user->id)->first();

        if ($existingVote) {
            if ($existingVote->vote_type == 0) {
                // User has already downvoted, so remove the vote
                $existingVote->delete();
                $question->increment('Upvotes'); // Increment by 1 (removing downvote)
                $message = 'Downvote removed successfully!';
                $userVote = null;
            } else {
                // User has upvoted, switch to downvote
                $existingVote->update(['vote_type' => 0]);
                $question->decrement('Upvotes', 2); // Adjust the count accordingly
                $message = 'Question downvoted successfully!';
                $userVote = 0;
            }
        } else {
            $question->votes()->create([
                'user_id' => $user->id,
                'vote_type' => 0
            ]);
            $question->downvote();
            $message = 'Question downvoted successfully!';
            $userVote = 0;
        }

        // Refresh the question to get updated upvotes count
        $question->refresh();

        // Check if it's an AJAX request
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => $message,
                'upvotes' => $question->Upvotes,
                'userVote' => $userVote
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function filterByTag(Request $request)
    {
        $selectedTags = $request->input('tags');
        $allTags = Question::all()->pluck('Tags');

        $tagCounts = DB::table('questions')
            ->select(DB::raw('json_extract(Tags, "$[*]") as tag'))
            ->pluck('tag')
            ->flatten()
            ->map(fn($tag) => json_decode($tag, true))
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc();

        $mostUsedTags = $tagCounts->keys()->take(10);

        if ($selectedTags) {
            $filtered_questions = Question::where(function ($query) use ($selectedTags) {
                foreach ($selectedTags as $tag) {
                    $query->orWhereJsonContains('Tags', $tag);
                }
            })->orderBy('created_at', 'desc')->paginate(5);
        } else {
            $filtered_questions = collect();
        }

        $all_questions = Question::orderBy('created_at', 'desc')->paginate(5);

        return view('questions.index', compact('filtered_questions', 'all_questions', 'mostUsedTags', 'selectedTags'));
    }

    public function loadMoreTags()
    {
        $tagCounts = DB::table('questions')
            ->select(DB::raw('json_extract(Tags, "$[*]") as tag'))
            ->pluck('tag')
            ->flatten()
            ->map(fn($tag) => json_decode($tag, true))
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc();

        $tagsToShow = $tagCounts->keys()->take(50);
        $all_questions = Question::orderBy('created_at', 'desc')->paginate(5);

        return view('questions.index', compact('all_questions', 'tagsToShow'));
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        if ($question->replies->isEmpty()) {
            $question->update(['Answered' => false]);
        }

        return redirect()->route('questions.index')->with('success', 'Question deleted successfully!');
    }
}
