<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Question;
use Illuminate\Support\Str;

class RealisticQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have some users
        if (User::count() < 5) {
            User::factory(10)->create();
        }
        
        $users = User::all();
        
        $tagsPool = [
            "php", "laravel", "javascript", "vue.js", "react", "angular", 
            "css", "tailwind", "bootstrap", "html", "python", "django", 
            "flask", "java", "spring-boot", "c++", "c", "c#", ".net", 
            "sql", "mysql", "postgresql", "mongodb", "redis", "docker", 
            "kubernetes", "aws", "linux", "git", "github", "api", 
            "rest", "graphql", "authentication", "security", "testing", 
            "phpunit", "debugging", "variables", "arrays", "objects"
        ];

        $titles = [
            "How to resolve dependency conflict in composer?",
            "Best practices for React query data fetching",
            "Understanding Eloquent relationships in Laravel",
            "Docker container exits immediately after starting",
            "How to vertically center a div with Flexbox?",
            "Difference between interface and abstract class in PHP",
            "Optimizing SQL queries for large datasets",
            "Getting CORS error when fetching from API",
            "How to implement real-time notifications with Pusher",
            "State management in Vue 3 using Pinia",
            "Securing REST API with JWT authentication",
            "How to setup CI/CD pipeline with GitHub Actions",
            "Understanding JavaScript Event Loop",
            "Implementing infinite scroll in a Laravel application",
            "Handling validation errors in a generic way",
            "Deploying a Python Flask app to AWS EC2",
            "How to use Tailwind CSS with existing Bootstrap project?",
            "Best way to handle file uploads in Node.js",
            "Understanding variable scope in C++",
            "How to mock external services in PHPUnit?",
        ];

        // create 50 randomized questions
        for ($i = 0; $i < 50; $i++) {
            $randomUser = $users->random();
            $numTags = rand(1, 5);
            $selectedTags = collect($tagsPool)->random($numTags)->values()->all();
            
            // Generate a somewhat relevant title or pick random
            $title = $titles[array_rand($titles)] . " - " . Str::random(5);
            
            Question::create([
                "user_id" => $randomUser->id,
                "UserName" => $randomUser->name,
                "EmailId" => $randomUser->email,
                "Title" => $title,
                "Content" => "I am facing a challenge with this topic. \n\n" . 
                             "I have tried looking at the documentation for " . implode(", ", $selectedTags) . " but I am still stuck. \n\n" . 
                             "Here is what I have tried so far:\n" . 
                             "1. Attempted the standard approach.\n" . 
                             "2. Searched StackOverflow.\n\n" . 
                             "Any help is appreciated!",
                "Tags" => $selectedTags, // Model casts to array/json automatically
                "Upvotes" => rand(0, 50),
                "Answered" => (bool)rand(0, 1),
                "created_at" => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}

