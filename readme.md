# Bold  Review Analytics - API

This repository is just a part of project for a code challenge, to run the project take a look in [https://github.com/davibusanello/bold-analytics-hub](https://github.com/davibusanello/bold-analytics-hub)

- [Bold Commerce - Code Challenge Reference](https://github.com/bold-commerce/review-syncer)
- [Frontend](https://github.com/davibusanello/bold-analytics-app): SPA using Vue.js

## API Stack

- PHP 7.0 (Laravel to provide an API & command line / scheduled job to sync information from API of Shopify)
- MySQL 5.7
- Apache

## Requirements

- PHP 7.0
- MySQL 5.7
- Composer

## How to use

- $ `cp .env-example .env` Copy .env-example to .env
- $ `composer install`
- $ `php artisan migrate:fresh` It will run migrations in the database
- $ `php artisan shopify:review-sync` (optional) For manually sync the reviews (Job is running every 30 minutes)
