#STOP THE CAP!

STOP THE CAP! is a multiplayer web game built with a Laravel backend and Vue frontend. 

Instructions:
Ensure you have the following installed on your computer:
PHP 8. (and Composer)
Node.js (and npm)
Google Chrome/Safari
Optionally, Herd
Installation
Install Composer Dependencies:
Change directory to Laravel
Execute this: composer install
Install Node Dependencies: npm install
Configure Environment Variables: cp .env.example .env
Open the .env file and update:


APP_URL=http://localhost:8000
Connect your local database


Generate Application Key: php artisan key:generate

Run Migrations and Seed the Database:
php artisan migrate --seed



Running the Project
To test locally, you must have two terminals open.
Terminal 1: Run the Frontend Dev Server
Change directory to laravel 
Execute command: npm run dev
Terminal 2: Run the Laravel Backend
Change directory to laravel 
Execute command: php artisan serve
Cmd+click the server given


Testing Multiplayer
Required: Have multiple search engines open or incognito and regular windows.
Copy this: http://127.0.0.1:8000


>Click on Create Game
>Click Generate New Game for Game Code
>Copy the Code and Click on Join Game: Do this for all search engines in use
>Have one extra window and navigate to Create Game, then click on Start Game
