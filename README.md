# Dev Diaries

To set up this project locally follow these steps:

1. Clone the repo
2. cd into the cloned repo
3. Start your xampp server
4. Set up the .env file (ensure DB and Mail settings are configured)
5. Run "npm install"
6. Run "composer install"
7. Run "php artisan key:generate"
8. Run "npm run build"
9. Run "php artisan migrate"
10. Run "php artisan serve"
11. In a separate terminal, run "php artisan queue:work" to handle email notifications
12. Visit your site at "http://localhost:8000" in your browser.

## Features

- User Registration & Authentication
    - Automatic approval for users with @jimsindia.org email domains
    - Manual admin approval required for other domains
- Admin Dashboard
    - View application statistics
    - Approve or Reject pending user registrations
    - Manage reported questions and replies
    - Bulk User Import via CSV
- Email Notifications
    - Welcome email sent automatically when an admin approves a user
    - Account credentials sent via email to users imported via CSV
    - Sequential email processing using background jobs
    - Password reset functionality

~Rajat Malhotra
