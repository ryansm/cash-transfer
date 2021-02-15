.PHONY: migrate migrate_refresh test db_seed

migrate:
	docker-compose run --rm php-fpm php artisan migrate

migrate_refresh:
	docker-compose run --rm php-fpm php artisan migrate:refresh

test:
	docker-compose run --rm php-fpm php artisan test

db_seed:
	docker-compose run --rm php-fpm php artisan db:seed
