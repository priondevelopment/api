# Prion Api (Lumen/Laraval 5 Package)

Prion API to connect to the database using API Credentials/Tokens and User Auth.

Tested on Lumen 5.6

## Installation

```
composer require "prion-development/api:5.6.*"
```

In config/app.php, add the following provider:
```
PrionDevelopment\Providers\ApiProviderService::class
```

## Automated Setup
Run the following command for automated setup.
`php artisan prionapi:setup`

Clear or reset your Laravel config cache.
`php artisan config:clear`
`php artisan config:cache`

Register the the following in the command scheduler (app/Console/Kernel.php):<br>
```
$schedule->command('prionapi:delete_token_expired')->everyMinute();
```

## License

Prion Api is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
