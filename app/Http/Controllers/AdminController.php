<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Jobs\SendAccountCreatedEmailJob;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'pending_users_count' => User::where('is_approved', false)->count(),
            'reported_questions_count' => Question::where('reported', true)->count(),
            'reported_replies_count' => Reply::where('reported', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function reportedItems()
    {
        // Use pagination to avoid loading all reported items at once
        $reportedQuestions = Question::select('id', 'UserName', 'user_id', 'Title', 'Content', 'Upvotes', 'reported', 'created_at', 'updated_at')
            ->where('reported', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $reportedReplies = Reply::select('id', 'question_id', 'UserName', 'user_id', 'Content', 'image_url', 'Upvotes', 'reported', 'created_at', 'updated_at')
            ->where('reported', true)
            ->with(['question:id,Title'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.reported', compact('reportedQuestions', 'reportedReplies'));
    }

    public function users()
    {
        $pendingUsers = User::where('is_approved', false)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users', compact('pendingUsers'));
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();

        Mail::to($user)->send(new WelcomeEmail($user));

        return redirect()->route('admin.users')->with('success', 'User ' . $user->name . ' approved successfully.');
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User ' . $user->name . ' rejected/deleted successfully.');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        $count = 0;
        DB::transaction(function () use ($data, &$count) {
            foreach ($data as $row) {
                if (count($row) < 3) continue;

                $name = trim($row[0]);
                $email = trim($row[1]);
                $password = trim($row[2]);

                if (strtolower($name) === 'name' && strtolower($email) === 'email') continue;

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

                if (User::where('email', $email)->exists()) continue;

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'is_approved' => true,
                    'email_verified_at' => now(),
                ]);

                SendAccountCreatedEmailJob::dispatch($user, $password);

                $count++;
            }
        });

        return redirect()->route('admin.users')->with('success', "$count users imported successfully.");
    }
}
