# Stop the Cap!

Stop the Cap! is a multiplayer web game with a Laravel backend and a Vue.js frontend. This repository contains the full-stack code and project structure used to build and run the game locally.

https://github.com/user-attachments/assets/cbf28cee-a823-466c-9345-4875cc47a1a5

Key highlights
- Full-stack project using Laravel (PHP) and Vue.js.
- Demonstrates environment configuration, database migrations & seeding, frontend build tooling, and a local development workflow for testing multiplayer functionality.
- Useful as a portfolio piece or resume project to show practical experience with modern web development tools.

Tech stack
- Backend: PHP 8+, Laravel
- Frontend: Vue.js, npm
- Tooling: Composer, npm, Laravel artisan
- Browser: Google Chrome / Safari
- Optional: Herd / Valet / other local dev helpers

Prerequisites
- PHP 8 or newer (and Composer)
- Node.js and npm
- A modern browser (Chrome, Safari, or Firefox)
- A local database (MySQL, MariaDB, SQLite, etc.)
- Optional: Herd or Laravel Valet for macOS

Quick setup (local development)
1. Clone the repository
   - git clone <this-repo-url>
   - cd stop-the-cap

2. Install backend dependencies
```bash
cd laravel
composer install
```

3. Install frontend dependencies
```bash
npm install
```

4. Environment setup
```bash
cp .env.example .env
# Open .env and update database settings and APP_URL if needed, e.g.:
# APP_URL=http://localhost:8000
```

5. Application key
```bash
php artisan key:generate
```

6. Database migrations & seeders
```bash
php artisan migrate --seed
```
This will create all necessary tables and seed example data used for testing the game.

Run the app locally
You will typically have two terminals running: one for the frontend dev server and one for the backend.

Terminal 1 — Frontend (assets + hot reload)
```bash
cd laravel
npm run dev
```

Terminal 2 — Backend (Laravel HTTP server)
```bash
cd laravel
php artisan serve
```
Note: php artisan serve prints the local URL (usually http://127.0.0.1:8000). Cmd/Ctrl+click that link to open the site.

Multiplayer testing (local)
- Open multiple browser windows or use a mix of regular and incognito windows to simulate multiple players.
- Navigate to the app (e.g., http://127.0.0.1:8000).
- Create a game:
  - Click "Create Game".
  - Click "Generate New Game" to get a game code.
  - Share the game code and use it to "Join Game" from each test window.
- Use one window to "Start Game" as the host once everyone has joined.

Repository structure (high level)
- laravel/ — backend Laravel application (controllers, migrations, seeders, routes)
- (frontend components are inside the Laravel project under resources/js or a dedicated frontend folder depending on structure)

Notes & troubleshooting
- If using a different host/port, update APP_URL in .env to match.
- Ensure database credentials in .env are correct before running migrations.
- If assets don’t load after npm run dev, stop the dev server and restart it; check console for build errors.
- If you prefer to use Herd/Valet, you can run the project using those tools instead of php artisan serve.

Contribution
- Open issues for bugs or feature requests.
- Pull requests are welcome — please describe the change and include testing steps.

License
- If you want to add a license, include a LICENSE file (MIT is common for portfolio projects).

Resume-ready bullets
Use one or more of the following on your resume or portfolio:
- Built a multiplayer web game using Laravel (PHP) and Vue.js; implemented environment setup, database migrations & seeding, and a local development workflow for multiplayer testing.
- Managed full-stack project lifecycle: dependency management (Composer, npm), environment configuration, and automated database setup with migrations and seeders.
- Demonstrated practical experience with frontend-backend integration, development servers, and multi-client testing for concurrent gameplay.

Contact
- Author: Kyle Abaya and Maya Leidler
- GitHub: https://github.com/kyleabaya/stop-the-cap
