To set up this project localy follow these steps:

1. Clone the repo
2. cd into the clonned repo
3. start your xampp server
4. set up the .env file
5. run "npm install"
6. run "composer install"
7. run "php artisan key:generate"
8. run "npm run build"
9. run "php artisan migrate"
10. run "php -S localhost:8000 -t public" or "php artisan serve"
11. visit your site at "http://localhost:8000" in your browser.

Notes:

-   For email, configure MAIL\_\* in `.env`. Optionally set `MAIL_TIMEOUT=10` to control SMTP timeouts.
-   Verification emails are dispatched after the HTTP response to avoid blocking requests. No queue worker is required for this.

~Rajat Malhotra
