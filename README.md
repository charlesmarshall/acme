# Overview

This console app provides commands for adding, deleting and displaying contents of a basket and is based on laravel zero, to make this a slimmer set of libraries to install for the limited test cases.

## Tools Used

List of technologies used to create this app

- PHP 7.4
- MySQL 5.7
- Laravel Zero
- Eloquent
- Dotenv

## Installation

Firstly, we need to install composer to include all the required library code and the setup the database.

### Composer

Within this directory, please run `composer install` to fetch all required vendor code correctly.

### Database

Please create a UTF-8 based database called `acme` (or change envrionment DB_DATABASE variable), then run the migration commands and seeders below (from this directory) to setup the database content

- `composer dump-autoload -o`
- `php acme migrate:fresh --seed`

The migration and seed command will load in the default products provided.

## Unit Tests

There are basic unit tests that cover the test scenarios, you can run these by calling php unit from this directory

- `./vendor/bin/phpunit`

# Usage

Outside of the unit tests there are console commands created to allow adding and removal from a pre-created basket.

## List

You can list all baskets active in the database simply running `php acme basket:list`. The seeding creates a test basket.

## Add

You can any number of products to the basket and select which basket to add them to by running

- `php acme basket:add {codeA,codeB} {--basket=id}`

If you specifiy a basket id that does not exist, the command will fall back to the default basket installed by the seeder. This would need to adjust for more production suitable code.

If a product code passed along does not exist, it is ignored and wont be added to the database

## Delete

The delete function mirrors the functionality of the Add command, with basket id and product codes being passed along.

- `php acme basket:delete {codeA,codeB} {--basket=id}`

Basket defaults to the seeded basket unless a correct basket id is passed along.

Codes have to be passed multiple times for each product, so to remove two R01 the command would look like

- `php acme basket:delete R01,R01`

# Summary

The console app is simplistic in how it handles discounts and delivery pricing, running multiple types of offers would require change to structure to allow for a one-to-many relationship between a product and a new offer data type and structure.

How the default basket is created and handled is not ideal for a production version as all requests fall over to a default basket - this is work around to avoid storing basket state on the command line.

