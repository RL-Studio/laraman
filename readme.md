# Laraman

Have you ever been building a huge API and you're sitting there with Postman to quickly look at your requests? It's tedious, right? So much copy-pasting routes and keeping track of them. Maybe at one point you started making some Postman collections? What if there was a better way, an easier way? There is. Laraman allows you to export all the routes that are registered in your application to a json file that can be imported into Postman.

## Installation
Install via composer:
```
composer require rl-studio/laraman 
```

Add the service provider to your `providers` array in `config/app.php`

```
'RLStudio\Laraman\ServiceProvider',
```

That's all!

## Usage

To run the command, simply use

```
php artisan laraman:export
```

This will place a `laraman-export.json` inside your `storage/app` folder. You are free to change the name of the file by specifying the filename as follows:

```
php artisan laraman:export --name=my-app
```