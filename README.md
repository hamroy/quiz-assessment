# Quiz & Assessment Management System

A monolithic web application for creating and taking quizzes. Administrators manage quizzes, questions, and answer options; users take published assessments and review their scored results.

## Features

- Admin quiz management: create, edit, publish, archive, and delete quizzes.
- Question and answer-option management.
- Public listing and detail pages for published quizzes.
- Authenticated quiz attempts, answer persistence, server-side evaluation, and result review.
- Role-based access for administrators and quiz-taking users.

## Tech Stack

- PHP 8.3+
- Laravel 13
- Livewire 3 and Volt
- Tailwind CSS and Vite
- MySQL 8
- Docker Compose (optional)

## Requirements

- PHP 8.3 or later with the extensions required by Laravel and the configured database driver.
- Composer
- Node.js 20+ and npm
- MySQL 8 (or Docker Desktop / Docker Engine with Compose)

## Installation

1. Clone the repository and enter the project directory:

   ```bash
   git clone https://github.com/hamroy/quiz-assessment.git
   cd quiz-assessment
   ```

2. Install PHP dependencies and create the local environment file:

   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows Command Prompt, use `copy .env.example .env` instead of `cp`.

3. Configure the database settings in `.env` (see [Environment](#environment)). Create the database if it does not already exist, then run migrations and seed the development accounts:

   ```bash
   php artisan migrate --seed
   ```

4. Install frontend dependencies and build assets:

   ```bash
   npm install
   npm run build
   ```

5. Start the application:

   ```bash
   php artisan serve
   ```

   Visit [http://localhost:8000](http://localhost:8000). For hot-reloaded frontend assets during development, run `npm run dev` in a second terminal.

For a fresh local setup, `composer run setup` automates dependency installation, environment/key setup, migrations, and frontend build. Review the script in `composer.json` before running it; configure `.env` and the database for your environment as needed.

## Environment

Copy `.env.example` to `.env` and set the values for your local environment. At minimum, configure:

| Variable | Purpose | Example |
| --- | --- | --- |
| `APP_NAME` | Application display name | `Quiz Assessment` |
| `APP_ENV` | Runtime environment | `local` |
| `APP_KEY` | Application encryption key; generate with `php artisan key:generate` | Generated value |
| `APP_URL` | Base URL used by the application | `http://localhost:8000` |
| `DB_CONNECTION` | Laravel database driver | `mysql` |
| `DB_HOST` | Database host | `127.0.0.1` locally, `mysql` in Compose |
| `DB_PORT` | Database port | `3306` |
| `DB_DATABASE` | Database name | `quiz_assessment` |
| `DB_USERNAME` | Database user | `root` locally; see Compose configuration for container setup |
| `DB_PASSWORD` | Database password | Set to match your database configuration |

Do not commit `.env` or production secrets.

## Database

The application uses MySQL. Schema migrations are in `database/migrations/`; `php artisan migrate` creates the tables. Run `php artisan db:seed` (or `php artisan migrate --seed`) to create development accounts:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@example.com` | `password` |
| User | `user@example.com` | `password` |

These are development credentials only. Change or remove them before deploying the application.

## Running the Application

### Local development

Start Laravel and Vite in separate terminals:

```bash
php artisan serve
npm run dev
```

### Docker Compose

The Compose configuration starts the app and MySQL services. Generate an application key and save it as `APP_KEY` in `.env` before starting the stack:

```bash
php artisan key:generate --show
# Copy the displayed key into .env as APP_KEY=<generated-key>
docker compose up -d --build
```

The app is exposed on port `8080` by default; set `APP_PORT` to change the host port. **Before starting**, reconcile the database password in `docker-compose.yml`: the app currently uses an empty root password, while the MySQL service sets the root password to `root_password`. Set the app's `DB_PASSWORD` to `root_password` (or configure both services with your own matching credentials). Do not expose these development credentials publicly.

After both services start, initialize the schema and development accounts:

```bash
docker compose exec app php artisan migrate --seed
```

Compose persists MySQL data in the `mysql_data` volume. To stop the services:

```bash
docker compose down
```

## Testing

Run the Laravel unit and feature test suites with PHPUnit:

```bash
php artisan test
```

Browser-based tests are described in `playwright.config.ts`, but Playwright and its test scripts are not currently included in `package.json`. Install and configure `@playwright/test` before attempting to run the E2E suite.

## Architecture

The application is organized by responsibility:

- `app/Models/` — Eloquent models and relationships.
- `app/Enums/` — domain values such as quiz status and user role.
- `app/Repositories/` — persistence queries and repository contracts.
- `app/Services/` — quiz, question, answer-option, and assessment workflows.
- `app/DTOs/` — structured assessment result data.
- `app/Livewire/` and `resources/views/livewire/` — Livewire/Volt pages, forms, and actions with Blade views.
- `routes/` — web, public, admin, and authentication route definitions.
- `database/migrations/`, `database/factories/`, and `database/seeders/` — schema and development data.
- `tests/` — PHPUnit unit and feature tests. A Playwright configuration exists, but the E2E test dependency and scripts are not currently set up.

Repository and service layers keep data access and business workflows out of the presentation views. Assessment correctness and scores are calculated server-side.
