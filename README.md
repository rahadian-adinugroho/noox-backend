# Noox Back-end

Backend for "Noox" collaborative thesis project.

### Installation

This project require at least PHP 5.6.3 to run.

Install the dependencies.

```sh
$ cd noox-backend
$ composer install
```

Generate key for the framework and API.

```sh
$ php artisan key:generate
$ php artisan jwt:generate
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
$ php artisan serve
```

### Todos

 - More API.
 - CMS.
 