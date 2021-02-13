.PHONY: migrate migrate_refresh test

migrate:
  docker-compose exec php-fpm php artisan migrate

migrate_refresh:
  docker-compose exec php-fpm php artisan migrate:refresh

test:
  docker-compose exec php-fpm php artisan test
