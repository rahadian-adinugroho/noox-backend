# Noox Back-end

Backend for "Noox" collaborative thesis project.

### Installation

This project require at least PHP 5.6.3 to run.

Install the dependencies.

```sh
$ cd noox-backend
$ composer install
```

Then open the .env file.

```sh
$ cp .env.example .env
$ [text editor] .env
```
Edit following entries:
>DB_CONNECTION=mysql
>DB_HOST=127.0.0.1
>DB_PORT=3306
>DB_DATABASE=mydb
>DB_USERNAME=myusername
>DB_PASSWORD=mypass

Generate key for the framework and API.

```sh
$ php artisan key:generate
$ php artisan jwt:generate
```

Run database migration.
Before migrating, make sure that you have created the database and change the .env file according to the database name.

```sh
$ php artisan migrate
```

Seed the database.
You may want to seed the database with some dummy data.

```sh
$ php artisan db:seed
```

And finally, start the server.

```sh
$ php artisan serve --host=[YOUR IP ADDRESS]
```

## Event Broadcasting

To enable event broadcasting. Make sure:
* You have started Redis server.
* You have installed laravel-echo-server globally from npm.
* Configure the laravel-echo-server.json according to your system configuration.
* Set the BROADCAST_DRIVER in .env to redis.
* Include socket.io.js & laravel-echo in the page with event listener.

### Starting the server

First, start both Laravel queue worker and listener.

```sh
$ cd noox-backend
$ php artisan queue:work
$ php artisan queue:listen
```

Then, start the Laravel server.

```sh
$ php artisan serve
```

Finally, start the laravel-echo-server.

```sh
$ laravel-echo-server start
```

### Todos

 - More API.
 - CMS.
