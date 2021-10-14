
This is a Laravel application to test scheduler using Sample Marketplace API from Despatch Cloud

## About Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Installation

To Clone this repository type:

via HTTPS
```
git clone https://github.com/ravs333/laravel-api-project.git
```
or

via SSH
```
git clone git@github.com:ravs333/laravel-api-project.git
```


After cloning, install dependencies using composer:
```
php composer update 
```


Setup .env file with correct database and API configurations. See sample .env file below:
```
APP_NAME=<YOUR APP NAME>
APP_ENV=local
APP_KEY=<YOUR APP KEY>
APP_DEBUG=true
APP_URL=<YOUR APP URL>

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<DATABASE NAME>
DB_USERNAME=<DATABASE USER>
DB_PASSWORD=<DATABASE PASS>

DESPATCH_CLOUD_MARKETPLACE_API_KEY= <YOUR API KEY>
DESPATCH_CLOUD_MARKETPLACE_API_URL= <YOUR API URL>
```


Install Database Tables Using following command:
```
php artisan migrate 
```

Create Log Files in storage/logs/ folder
```
1. api.log
2. laravel.log 
```

## Usage
Start Scheduler by running following command:
```
php artisan schedule:run
```


## Security Vulnerabilities

If you discover a security vulnerability, please send an e-mail to Ravindra Singh via [ravs333@gmail.com](mailto:ravs333@gmail.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
