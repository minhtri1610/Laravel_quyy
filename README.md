# Setup


## install laravel
docker compose exec web composer install

## create db
docker compose exec mysql mysql -u root -p
CREATE DATABASE base_db;

## create table
docker compose exec web php artisan migrate

docker compose exec web php artisan make:service UserService
docker compose exec web php artisan make:repository UserService
docker compose exec web php artisan make:model Entities/User

## create controller
docker compose exec web php artisan make:controller {/{Name}Controller}
#docker compose exec web php artisan make:controller Admin/UserController
#docker compose exec web php artisan make:controller Client/HomeController


## create model
docker compose exec web php artisan make:model /{Name}
#docker compose exec web php artisan make:model Entities/User

## create service
docker compose exec web php artisan make:service /{Name}
docker compose exec web php artisan make:service UserService

## create repository
docker compose exec web php artisan make:repository /{Name}
docker compose exec web php artisan make:repository UserService

## key
docker compose exec web php artisan key:generate

## storage
docker compose exec web php artisan storage:link

## premission
docker compose exec web chmod -R 777 storage bootstrap/cache


## fresh:
docker compose exec web php artisan migrate:fresh --seed

docker compose exec web php artisan migrate:fresh
docker compose exec web php artisan migrate:refresh

## seed:
docker compose exec web php artisan db:seed
docker compose exec web php artisan make:seeder createRolesSeeder


## url
http://localhost:3000/


