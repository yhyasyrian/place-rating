# Place Rating
A simple place rating system with Laravel for insert place and rating it and ability to add review for place.

# Features
- Show place on map with and ability to add new place from map with leaflet
- Responsive location on various screens
- easy dashboard for admin to manage places and reviews, and ability to add user with role and permisions
- Use livewire for dynamic some commands
- Search engine optimization is done through a tool `artesaos/seotools`

# Installation
You can install this project in your server through follow this commands
```bash
git clone https://github.com/yhyasyrian/place-rating
cd place-rating
composer install
npm i
npm run build
php artisan storage:link
```

# Configuration
You can configure the project in the `.env` file and generate key for project
```bash
cp .env.example .env
php artisan key:generate
```
## Migrate

```bash
php artisan migrate
```
and run this command to seed the database
```bash
php artisan db:seed
```
## Cache
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

# Contributing
Contributions are welcome!
