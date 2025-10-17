<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Question;
use App\Models\Reply;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        // Optimize: Select only necessary columns and paginate
        $questions = $user->questions()
            ->select('id', 'UserName', 'user_id', 'EmailId', 'Title', 'Upvotes', 'Answered', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Use groupBy at DB level for better performance
        $replies = $user->replies()
            ->select('replies.id', 'replies.question_id', 'replies.UserName', 'replies.user_id', 'replies.EmailId', 'replies.Content', 'replies.image_url', 'replies.Upvotes', 'replies.created_at')
            ->with(['question:id,Title'])
            ->orderBy('replies.created_at', 'desc')
            ->paginate(5);

        return view('profile.show', compact('user', 'questions', 'replies'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
