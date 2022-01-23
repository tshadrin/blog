up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear blog-clear docker-pull docker-build docker-up blog-init
test: blog-test
test-unit: blog-test-unit

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

blog-clear:
    docker run --rm -v ${PWD}/:/app --workdir=/app alpine rm -f .ready

docker-pull:
	docker-compose pull --include-deps

docker-build:
	docker-compose build

#INITIALIZATION
blog-init: blog-composer-install blog-assets-install blog-ready

blog-composer-install:
	docker-compose run --rm blog-php-cli composer install

blog-assets-install:
	docker-compose run --rm blog-node yarn install
	docker-compose run --rm blog-node npm rebuild node-sass

blog-ready:
	docker run --rm -v ${CURRENT_DIR}/:/app --workdir=/app alpine touch .ready
###
#ASSETS
blog-assets-dev:
	docker-compose run --rm blog-node yarn encore dev
###
#MIGRATIONS
blog-migrations:
	docker-compose run --rm blog-php-cli php bin/console doctrine:migrations:migrate --no-interaction

blog-migrations-validate:
	docker-compose run --rm blog-php-cli php bin/console doctrine:schema:validate --no-interaction
###
#FIXTURES
blog-fixtures:
	docker-compose run --rm blog-php-cli php bin/console doctrine:fixtures:load --no-interaction
###
#CODESTYLE
blog-lint:
	docker-compose run --rm blog-php-cli composer lint  --no-interaction

blog-cs-check:
	docker-compose run --rm blog-php-cli composer phpcs  --no-interaction

blog-cs-fix:
	docker-compose run --rm blog-php-cli composer phpcbf  --no-interaction
###