install_and_start:
	composer install
	cp .env.example .env
	php artisan sail:install
	./vendor/bin/sail up -d
	docker exec auth_service_app php artisan key:generate
	docker exec auth_service_app php artisan jwt:secret --force

up:
	./vendor/bin/sail up -d

rebuild:
	docker compose up -d --no-deps --build auth_service

exec:
	docker exec -it auth_service_app bash

front:
	docker exec -it auth_service_app npm run dev

db-seed:
	docker exec auth_service_app php artisan db:seed

stop:
	./vendor/bin/sail down

setup-hooks:
	docker exec -it auth_service_app git config core.hooksPath .githooks

solo:
	docker exec -it auth_service_app php artisan solo

setup-tests:
	touch database/database.sqlite
	docker exec auth_service_app cp .env.testing.example .env.testing
	docker exec auth_service_app php artisan key:generate --env=testing
	docker exec auth_service_app php artisan jwt:secret --env=testing --force

run-tests:
	docker exec auth_service_app php artisan test --colors=always --env=testing

api-docs-generate:
	docker exec auth_service_app php artisan l5-swagger:generate
