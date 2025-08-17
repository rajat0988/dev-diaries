<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Question;
use App\Models\Reply;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create some users
        $user1 = User::create(['name' => 'Rajat', 'email' => 'rajat@example.com', 'password' => Hash::make('password')]);
        $user2 = User::create(['name' => 'Aarav', 'email' => 'aarav@example.com', 'password' => Hash::make('password')]);
        $user3 = User::create(['name' => 'Vivaan', 'email' => 'vivaan@example.com', 'password' => Hash::make('password')]);
        $user4 = User::create(['name' => 'Aditya', 'email' => 'aditya@example.com', 'password' => Hash::make('password')]);
        $user5 = User::create(['name' => 'Vihaan', 'email' => 'vihaan@example.com', 'password' => Hash::make('password')]);


        // Create some questions
        $question1 = Question::create([
            'user_id' => $user1->id,
            'UserName' => $user1->name,
            'EmailId' => $user1->email,
            'Title' => 'How to center a div in CSS?',
            'Content' => 'I have been trying to center a div both horizontally and vertically, but I am not able to do it. I have tried using flexbox and grid, but it is not working. Can someone please help me with this?',
            'Tags' => json_encode(['css', 'html', 'web-development']),
        ]);

        $question2 = Question::create([
            'user_id' => $user2->id,
            'UserName' => $user2->name,
            'EmailId' => $user2->email,
            'Title' => 'What is the difference between a variable and a constant in PHP?',
            'Content' => 'I am new to PHP and I am confused between variables and constants. Can someone please explain the difference between them with an example?',
            'Tags' => json_encode(['php', 'programming', 'backend']),
        ]);

        $question3 = Question::create([
            'user_id' => $user3->id,
            'UserName' => $user3->name,
            'EmailId' => $user3->email,
            'Title' => 'How to connect to a MySQL database using Python?',
            'Content' => 'I am trying to connect to a MySQL database using Python, but I am getting an error. I have installed the mysql-connector-python library, but it is still not working. Can someone please help me with this?',
            'Tags' => json_encode(['python', 'mysql', 'database']),
        ]);

        // Create some replies
        Reply::create([
            'user_id' => $user4->id,
            'question_id' => $question1->id,
            'UserName' => $user4->name,
            'EmailId' => $user4->email,
            'Content' => 'You can use flexbox to center a div both horizontally and vertically. Here is an example: `display: flex; justify-content: center; align-items: center;`',
        ]);

        Reply::create([
            'user_id' => $user5->id,
            'question_id' => $question1->id,
            'UserName' => $user5->name,
            'EmailId' => $user5->email,
            'Content' => 'You can also use grid to center a div. Here is an example: `display: grid; place-items: center;`',
        ]);

        Reply::create([
            'user_id' => $user1->id,
            'question_id' => $question2->id,
            'UserName' => $user1->name,
            'EmailId' => $user1->email,
            'Content' => 'A variable is a storage location that can be changed, while a constant is a storage location that cannot be changed. For example, you can define a variable like `$name = \'John\'` and then change it later like `$name = \'Doe\'`, but you cannot change the value of a constant once it is defined.',
        ]);
    }
}
