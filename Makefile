.PHONY: start up migrate migrate_refresh test db_seed passport-install queue-work queue-restart

start: migrate db_seed queue-work

up:
	docker-compose up -d

migrate:
	docker-compose exec php-fpm php artisan migrate

migrate_refresh:
	docker-compose exec php-fpm php artisan migrate:refresh

test:
	docker-compose exec php-fpm php artisan test

db_seed:
	docker-compose exec php-fpm php artisan db:seed

passport-install:
	docker-compose exec php-fpm php artisan passport:install --uuids
	docker-compose exec php-fpm php artisan passport:client --password

queue-work:
	docker-compose exec php-fpm php artisan queue:work

queue-restart:
	docker-compose exec php-fpm php artisan queue:restart
