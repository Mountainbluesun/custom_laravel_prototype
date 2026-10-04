# Stock Manager – Laravel

Stock management application built with Laravel.
This project demonstrates my backend skills (CRUD, security, tests, audit).

## 🚀 Features

- Product management (CRUD)
- Stock movement management (incoming / outgoing)
- Automatic stock calculation after each movement
- Global movement history
- CSV export of movements (restricted access)

## 🔐 Security & Authorization

- Authentication with Laravel Breeze
- Authorization handled with **Policies**
- Restricted access:
  - Standard users: products only
  - Administrator: access to the global history (`/movements`) and CSV export (`/movements/export`)
- `can:` middleware protects sensitive routes
- Custom 403 page for non-admin users

## 🧪 Tests

- Feature tests with **Pest**, run with `php artisan test`
- Run automatically on every push through **GitHub Actions**
- Access control: 403 for non-admin users, 200 for admins on the global history and CSV export
- Stock logic:
  - an incoming entry increases the stock and records a movement with the resulting stock
  - an outgoing entry decreases the stock
  - an outgoing entry larger than the current stock is rejected, with no change to the stock or the history
  - invalid input (zero or non-numeric quantity, unknown type) is rejected
  - guests are redirected to the login page and nothing is recorded
- Authentication and profile flows (Laravel Breeze) are covered by its default tests

## 📸 Screenshots

### Admin – access granted (200)
![Admin 200](laravel/docs/screenshots/admin_movements_200.png)

### User – access denied (403)
![User 403](laravel/docs/screenshots/user_movements_403.png)

## 🛠️ Tech stack

- Laravel 12
- PHP 8.3
- SQLite
- Blade + Tailwind
- Laravel Breeze
- Pest

## ⚙️ Local installation

Requirements: PHP 8.3, Composer, Node.js and npm.

```bash
git clone https://github.com/Mountainbluesun/custom_laravel_prototype.git
cd custom_laravel_prototype/laravel
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
php artisan serve
```

The app is then available at http://127.0.0.1:8000.
