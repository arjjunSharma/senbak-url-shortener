<p align="center"><a href="#"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="320" alt="Senbak URL Shortener"></a></p>

## **Project**: Senbak URL Shortener

This repository contains a small Laravel application for creating and resolving short URLs with role-based access controls. The project includes tests (PHPUnit/Laravel) that you can run locally.

**This README explains how to set up the project locally for development and testing.**

**Prerequisites**
- `PHP` 8.0+ (match the version used in your environment)
- `Composer`
- `SQLite` or another database supported by Laravel (MySQL/Postgres)
- `Node.js + npm` (optional, for building frontend assets)

**Quick Setup (copy/paste)**
1. Clone the repo:

```bash
git clone https://github.com/USERNAME/REPO.git
cd REPO
```

2. Install PHP dependencies:

```bash
composer install
```

3. Copy environment and generate app key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Use SQLite for local testing (recommended) or configure `DB_*` in `.env`.

To use SQLite:

```bash
touch database/database.sqlite
sed -i '' "s/DB_CONNECTION=.*/DB_CONNECTION=sqlite/" .env || sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=sqlite/" .env
sed -i '' "s/DB_DATABASE=.*/DB_DATABASE=database\/database.sqlite/" .env || sed -i "s/DB_DATABASE=.*/DB_DATABASE=database\/database.sqlite/" .env
```

5. Run migrations and seeders (creates sample data used by tests and local app):

```bash
php artisan migrate:fresh --seed
```

6. (Optional) Install JS deps and build assets:

```bash
npm install
npm run build
```

7. Serve the application locally:

```bash
php artisan serve --port=8002
```

Open `http://127.0.0.1:8002` in your browser.

**Run the test suite**

The project uses PHPUnit via Laravel's test runner. To run tests:

```bash
php artisan test
# or directly
./vendor/bin/phpunit
```

If you see test failures that mention routes or missing factories, try running:

```bash
php artisan migrate:fresh --seed
php artisan test
```

**Common troubleshooting**
- If you get a `302` when the test expected `200` for the root route (`/`), the app redirects guests to `/login`. Tests may expect a redirect depending on authentication; check the failing test to confirm the expected behavior.
- If gates/authorization are failing with `Call to undefined method ...::authorize()`, ensure `app/Http/Controllers/Controller.php` contains the `AuthorizesRequests` trait (it does in this repo after recent fixes).
- If factories fail (e.g. `Company::factory()`), ensure model classes use `HasFactory`.

**Git / Deployment**
- I initialized a local git repository. To publish to GitHub:

```bash
# create a repo on GitHub, then:
git remote add origin git@github.com:USERNAME/REPO.git
git push -u origin main
```

Or use the GitHub CLI:

```bash
gh repo create USERNAME/REPO --public --source=. --remote=origin --push
```

**Files of interest**
- `app/Models/User.php` — role constants and helpers
- `app/Models/Company.php` — uses `HasFactory`
- `app/Http/Controllers/ShortUrlController.php` — listing/creating/resolving short URLs
- `tests/Feature/ShortUrlPermissionsTest.php` — permissions test coverage

