<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## This project built with Laravel 10 + Livewire 3 + Filament 3  <br>

- Support Light & Dark theme
- Support permission and roles for users
- Support Arabic & English language 
- Changing filament theme
- Support SPA (Single Page Application)
- If you like this work you can <a href="https://github.com/akramghaleb">see more here</a>

## Installation

Clone the repository

```
git clone https://github.com/akramghaleb/permissions-roles-filament-admin-panel.git
```

Switch to the repo folder

```
cd permissions-roles-filament-admin-panel
```

Install all the dependencies using composer

```
composer install
```

Copy the example env file and make the required configuration changes in the .env file

```
cp .env.example .env
```

Generate a new application key

```
php artisan key:generate
```

Run the database migrations (**Set the database connection in .env before migrating**)

```
php artisan migrate
```

Start the local development server

```
php artisan serve
```

You can now access the server at http://localhost:8000

<br><br>
  
