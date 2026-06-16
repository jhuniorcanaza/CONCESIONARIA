<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 🚗 Laracar - Laravel Car Marketplace

Welcome to **Laracar**, a Laravel 11 project designed to showcase a car marketplace platform. This project demonstrates the use of Laravel's robust features and modern web development practices.

🌐 **Live Demo**: [https://laracar.sv2109.com/](https://laracar.sv2109.com/)

---

## 🚀 Features

- **User Authentication**: Secure login, registration, and password reset functionality.
- **Social Authentication**: Login via Google and Facebook using Laravel Socialite.
- **Car Listings**: Add, edit, delete, and view car listings with detailed specifications.
- **Image Uploads**: Drag-and-drop image uploads with sorting and preview functionality using Dropzone.js.
- **Favorite Cars**: Add cars to a personal watchlist for easy access.
- **Search and Filtering**: Search cars by maker, model, price range, year, mileage, and more.
- **Pagination**: User-friendly pagination for car listings.
- **Dynamic Forms**: Cascading dropdowns for dependent fields like maker and model, state and city.
- **Custom Features**: Manage car features like ABS, air conditioning, GPS, and more.
- **Localization**: Support for multi-language content.
- **Soft Deletes**: Recoverable deletion of cars and related data.
- **Event-Driven Notifications**: Trigger notifications when a car is created.
- **Custom Console Commands**: Automate tasks using Laravel's Artisan commands.
- **Extensive Testing**: Feature tests to ensure application reliability.

---

## 🛠️ Technologies Used

- **Backend**: Laravel 11 (PHP 8.2)
- **Frontend**: Blade templates, Tailwind CSS, and Alpine.js
- **Database**: MySQL with Eloquent ORM
- **Caching**: Redis for optimized performance
- **File Storage**: Laravel's filesystem for managing car images.
- **Testing**: PHPUnit for feature testing.
- **Version Control**: Git and GitHub for collaboration.
- **Deployment**: Nginx, PHP-FPM, and Composer.

---

## 📚 Laravel Features in Use

### Authentication
- Built-in Laravel authentication for user management.
- Social authentication using Laravel Socialite for Google and Facebook.

### Eloquent ORM
- Used for database interactions with relationships like `hasMany`, `belongsTo`, and `belongsToMany`.

### Events and Listeners
- Custom events like `CarCreated` trigger notifications and other actions.

### Custom Actions
- Encapsulated business logic in reusable action classes like `StoreCarAction` and `UpdateCarAction`.

### Custom Casts
- `BooleanToDateCast` for transforming boolean values into date formats.

### Policies
- Authorization logic implemented using Laravel Policies.

### Services
- Service classes like `CarFormService` for handling reusable logic.

### DTOs
- Data Transfer Objects (DTOs) for structured data handling.

### Mail
- Email notifications for user interactions.

### Console Commands
- Custom Artisan commands for automating tasks.

### Testing
- Feature tests for car creation, updates, and database assertions.

### Middleware
- Protect routes with authentication middleware.

### Soft Deletes
- Recoverable deletion of cars and related images.

### Task Scheduling
- Cron jobs configured via Laravel's task scheduler for periodic tasks.

## File Uploads
- Secure file uploads with validation and storage in the `public` disk.

### Queues and Jobs
- Background processing for time-intensive tasks like email sending.

### Notifications
- Email and database notifications for user actions and updates.

### Query Scopes
- Custom query scopes for filtering cars by various criteria.

### Pagination
- Laravel's built-in pagination for car listings.

---

## 📖 How to Run Locally

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/laracar.git
   cd laracar
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Configure environment:
   - Copy `.env.example` to `.env` and update database credentials.

4. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

5. Start the development server:
   ```bash
   php artisan serve
   ```

6. Access the application at `http://localhost:8000`.

---

## 🤝 Contributing

Contributions are welcome! Feel free to fork the repository and submit a pull request.

---

## 📜 License

This project is open-source and available under the [MIT License](LICENSE).
