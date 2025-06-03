install_and_start:
	herd isolate 8.4
	herd composer install
	cp .env.example .env
	docker compose up -d
	herd php artisan key:generate
	herd php artisan jwt:secret --force

up:
	docker compose up -d

rebuild:
	docker compose up -d --no-deps --build app

exec:
	docker exec -it auth_service_app bash

front:
	npm run dev

db-seed:
	herd php artisan db:seed

stop:
	docker compose down

setup-hooks:
	git config core.hooksPath .githooks

solo:
	herd php artisan solo

setup-tests:
	touch database/database.sqlite
	cp .env.testing.example .env.testing
	herd php artisan key:generate --env=testing
	herd php artisan jwt:secret --env=testing --force
	herd php artisan migrate:fresh --env=testing
	herd php artisan config:clear --env=testing

run-tests:
	herd php artisan test --colors=always --env=testing

api-docs-generate:
	herd php artisan l5-swagger:generate
