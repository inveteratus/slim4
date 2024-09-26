# Slim 4 Demo

A simple demonstration of a working Slim 4 application with basic authentication.

## Installation

```shell
git clone git@github.com:inveteratus/slim4.git
cd slim4
cp env.example .env
composer up
```

After a moment, you should be able to access the application by pointing your browser at http://localhost:8000

Once you can see the index page in your browser, and after a minute or so has passed after first running `composer up`,
you can create the database with:

```shell
composer db.import
```

Now you should be able to register a new account which will automatically log you into the application. You can really
only logout from there, but you should be able to login with the credentials you provided during registration.

## Database management

The database can be managed by accessing http://localhost:8080 in your browser. Login with th following credentials:

* System: `MySQL`
* Server: `db`
* Username: `slim4`
* Password: `secret`
* Database: `slim4`

Any changes to the database can be written back to `schema.sql` with:

```shell
composer db.export
```

** Testing

Static analysis with [PHPStan](https://phpstan.org/) can be checked with:

```shell
composer analyse
```
