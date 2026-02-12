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
        \Illuminate\Support\Facades\Log::info('Importing file: ' . $file->getRealPath());

        $count = 0;

        try {
            DB::transaction(function () use ($file, &$count) {
                if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        \Illuminate\Support\Facades\Log::info("Processing row: " . json_encode($row));

                        if (count($row) < 3) {
                            \Illuminate\Support\Facades\Log::info("Row skipped: insufficient columns");
                            continue;
                        }

                        $name = trim($row[0]);
                        $email = trim($row[1]);
                        $password = trim($row[2]);

                        if (strtolower($name) === 'name' && strtolower($email) === 'email') {
                            \Illuminate\Support\Facades\Log::info("Row skipped: Header detected");
                            continue;
                        }

                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            \Illuminate\Support\Facades\Log::info("Row skipped: Invalid email - $email");
                            continue;
                        }

                        if (User::where('email', $email)->exists()) {
                            \Illuminate\Support\Facades\Log::info("Row skipped: Email exists - $email");
                            continue;
                        }

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
                    fclose($handle);
                }
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Import failed: ' . $e->getMessage());
            return redirect()->route('admin.users')->with('error', 'Import failed: ' . $e->getMessage());
        }

        \Illuminate\Support\Facades\Log::info("Import completed. Created $count users.");
        return redirect()->route('admin.users')->with('success', "$count users imported successfully.");
    }
}
