<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Finance Management System (FMS)

FMS is a professional-grade web application built with Laravel 12, designed to manage complex financial activities across multiple business entities. The system provides a centralized dashboard for tracking transactions, generating automated reports, and analyzing cash flow with high precision.

Key technical implementations in this project include:

- **Multi-Business Scoping**: Isolate financial records between different business units under a single account.
- **Service-Oriented Architecture**: Implementation of Service Providers and View Composers for efficient global data distribution.
- **Advanced Financial Analytics**: Interactive visual representation of trends using Chart.js.
- **Automated Reporting**: Professional financial statements exported to PDF format.
- **Dynamic UI Components**: Context-aware navigation and sidebar management for enhanced user experience.

## Technical Stack

FMS leverages the power of the modern Laravel ecosystem to provide a robust and secure financial platform:

- **Framework**: [Laravel 12.x](https://laravel.com/docs/12.x)
- **PHP Version**: 8.2+
- **Frontend Engine**: [Blade Templates](https://laravel.com/docs/blade) & [Tailwind CSS](https://tailwindcss.com)
- **Asset Management**: [Vite](https://laravel.com/docs/vite)
- **Database**: MySQL / MariaDB

## Installation & Setup

To get started with FMS, ensure your environment meets the requirements (PHP 8.2+, Composer, Node.js) and follow these steps:

1. **Clone the repository**: `git clone https://github.com/haiitsmee/sistem-keuangan.git`
2. **Install dependencies**: `composer install` and `npm install && npm run build`
3. **Configure environment**: Copy `.env.example` to `.env` and run `php artisan key:generate`
4. **Database setup**: Configure your DB credentials in `.env` and run `php artisan migrate --seed`
5. **Launch server**: `php artisan serve`

## Learning the Architecture

If you are interested in how the global data (like the business list in the sidebar) is handled, you can explore the following directory:

- `app/Providers/ViewServiceProvider.php`: Contains the View Composer logic.
- `app/Models/Business.php`: The core entity for multi-business management.
- `resources/views/components/`: Directory for reusable UI components.

## Security Vulnerabilities

If you discover a security vulnerability within this application, please open an issue in this repository or contact the maintainer directly. All security concerns will be addressed promptly.

## License

The Finance Management System is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
