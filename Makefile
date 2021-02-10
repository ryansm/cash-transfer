.PHONY: make_migration migrate migrate_refresh

make_migration:
  docker-compose exec php-fpm php artisan make:migration

migrate:
  docker-compose exec php-fpm php artisan migrate

migrate_refresh:
  docker-compose exec php-fpm php artisan migrate:refresh
