# Makefile

init:
	cp .env.dist .env && cp ./app/.env.dist ./app/.env
	docker-compose build
	docker-compose up -d
	sleep 10
	docker-compose exec php-fpm composer install
	docker-compose exec php-fpm bin/console doctrine:migrations:migrate -n
	docker-compose exec php-fpm bin/console doctrine:fixtures:load -n