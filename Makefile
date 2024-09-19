install_and_start:
	docker compose up -d
	docker exec auth_service_app php artisan key:generate
	docker exec auth_service_app php artisan jwt:secret --force

start:
	docker compose up -d

rebuild:
	docker compose up -d --no-deps --build app

db-seed:
	docker exec auth_service_app php artisan db:seed

stop:
	docker compose down -v

tests-setup:
	docker exec auth_service_app cp .env.testing.example .env.testing
	docker exec auth_service_app php artisan key:generate --env=testing
	docker exec auth_service_app php artisan jwt:secret --env=testing --force

tests:
	docker exec auth_service_app ./vendor/bin/pest

api-docs-generate:
	docker exec auth_service_app php artisan l5-swagger:generate
