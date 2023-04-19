# Wateract/Allikad

#### Laravel 8 + Inertia + Vue 2 + TailwindCSS stack

## Installation
### Prerequisites
Requires Docker Desktop app
https://www.docker.com/products/docker-desktop
### Procedure
```
composer install
```
Configure `.env`
```
cp .env.example .env
php artisan key:generate
```
Launch Sail
```
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
sail up
sail npm install
sail npm run dev
```
Run migrations
```
sail artisan migrate --seed
sail artisan storage:link
```

## Run Sail
`./vendor/bin/sail up`

Go to
http://localhost

## Additional configuration
See required keys in `.env.example` file, the following specific keys are required for the app to work:
```
MIX_GOOGLE_MAPS_API_KEY=
MIX_APP_COUNTRY=
MIX_KARTES_LV_API_KEY=
FB_PIXEL_ID=
```
