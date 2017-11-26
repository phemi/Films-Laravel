<p align="center"># Film Shop</p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
</p>

## About Film Shop

Film Shop is a simple application developed to show how to use RESTful API, DB, auth and form functionality in Laravel (and a little javascript).


## API Endpoints

- POST /api/v1/register
- POST /api/v1/login
- GET /api/v1/films
- GET /api/v1/films/{film-slug}
- POST /api/v1/comment


## Installation Requirements

- Php 7.0+ (Curl enabled)
- Mysql


## Installation Guide

- Clone the repository
- Properly configure the env file ( Database, and other config. Don't forget to new included variables: API_BASE_URI, IMAGE_PATH)
- Update. (Run: Composer update)
- Migrate the tables (Run: php artisan migrate)
- Initialise Passport. (Run: php artisan passport:install)
- Seed the database (Run: php artisan db:seed)
k you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities/Bugs

If you discover a security vulnerability or bug, do reach out. :)

## License

The Film-Shop is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
