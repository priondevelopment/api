# Prion Api (Lumen/Laraval 5 Package)

Prion API to connect to the database using API Credentials/Tokens and User Auth.

Tested on Lumen 5.6

## Installation

`composer require "prion-development/api:5.6.*"`

In config/app.php, add the following provider:
PrionDevelopment\Providers\PrionApiProviderService::class

Publish configuration files
php artisan vendor:publish --tag="prionusers"

Clear or reset your Laravel config cache.
php artisan config:clear
php artisan config:cache


## License

Prion Api is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
