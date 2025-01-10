# Project Name 

## Overview

This project is a Laravel application that includes various models to manage different entities such as Users, Posts, Products, Appointments, and more. Below is a brief overview of the key models and their functionalities.

## Models

### User

The `User` model represents the users of the application. It includes attributes like `name`, `email`, `password`, `customer_id`, and `role`.

- **File:** [app/Models/User.php](app/Models/User.php)
- **Policies:** [app/Policies/UserPolicy.php](app/Policies/UserPolicy.php)

### Post

The `Post` model represents the posts created by users. It includes attributes like `title`, `content`, and `author_id`.

- **File:** [app/Models/Post.php](app/Models/Post.php)
- **Resource:** [app/Filament/App/Resources/PostResource.php](app/Filament/App/Resources/PostResource.php)
- **Policies:** [app/Policies/PostPolicy.php](app/Policies/PostPolicy.php)

### Product

The `Product` model represents the products available in the application. It includes attributes like `name`, `description`, `price`, and `stock`.

- **File:** [app/Models/Product.php](app/Models/Product.php)
- **Policies:** [app/Policies/ProductPolicy.php](app/Policies/ProductPolicy.php)

### Appointment

The `Appointment` model represents the appointments scheduled in the application. It includes attributes like `date`, `time`, `user_id`, and `status`.

- **File:** [app/Models/Appointment.php](app/Models/Appointment.php)
- **Policies:** [app/Policies/AppointmentPolicy.php](app/Policies/AppointmentPolicy.php)

### ProductCategory

The `ProductCategory` model represents the categories for products. It includes attributes like `name` and `description`.

- **File:** [app/Models/ProductCategory.php](app/Models/ProductCategory.php)
- **Policies:** [app/Policies/ProductCategoryPolicy.php](app/Policies/ProductCategoryPolicy.php)

## Seeders

### SectorSeeder

The `SectorSeeder` seeds the database with initial data for sectors.

- **File:** [database/seeders/SectorSeeder.php](database/seeders/SectorSeeder.php)

## Widgets

### StatsOverview

The `StatsOverview` widget provides an overview of various statistics like the count of appointments, posts, and products.

- **File:** [app/Filament/App/Widgets/StatsOverview.php](app/Filament/App/Widgets/StatsOverview.php)

## Helpers

### hasModelPermission

The `hasModelPermission` function checks if a user has permission to perform actions on a model.

- **File:** [app/Helpers/helper.php](app/Helpers/helper.php)

## Environment Configuration

The environment variables are configured in the `.env` file. An example configuration is provided in the `.env.example` file.

- **File:** [.env.example](.env.example)

## Installation

1. Clone the repository.
2. Run `composer install` to install PHP dependencies.
3. Run `npm install` to install JavaScript dependencies.
4. Copy `.env.example` to `.env` and configure your environment variables.
5. Run `php artisan migrate` to run the database migrations.
6. Run `php artisan db:seed` to seed the database.

## Running the Application

To start the application, run:

```sh
php artisan serve