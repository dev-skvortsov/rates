docker-exec:
	docker exec -it app bash

docker-build:
	docker compose up --build -d ;\
	docker exec -it app bash -c "composer install"; \
	make run-migrations

run-migrations:
	docker exec -it app bash -c "php bin/console --no-interaction doctrine:migrations:migrate"
