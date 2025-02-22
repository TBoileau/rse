composer:
	composer valid

eslint:
	npx eslint assets/

stylelint:
	npx stylelint "assets/styles/**/*.scss"

twig:
	php bin/console lint:twig src/Shared/Infrastructure/Symfony/Resources/templates src/Security/Infrastructure/Symfony/Resources/templates
	vendor/bin/twigcs src/Shared/Infrastructure/Symfony/Resources/templates src/Security/Infrastructure/Symfony/Resources/templates

phpmd:
	vendor/bin/phpmd src/ text .phpmd.xml

phpinsights:
	vendor/bin/phpinsights --no-interaction

phpcpd:
	vendor/bin/phpcpd src/

phpstan:
	php vendor/bin/phpstan analyse -c phpstan.neon src --no-progress

fix:
	npx eslint assets/ --fix
	npx stylelint "assets/styles/**/*.scss" --fix
	vendor/bin/php-cs-fixer fix

container:
	php bin/console lint:container

analyse:
	make composer
	make eslint
	make stylelint
	make twig
	make container
	make phpcpd
	make phpmd
	make phpinsights
	make phpstan

.PHONY: tests
tests:
	php bin/console cache:clear --env=test
	vendor/bin/behat
	php bin/phpunit --testdox --testsuite=unit,functional

profile:
	php bin/console cache:clear --env=test
	make prepare env=test
	php bin/phpunit --testdox --testsuite=e2e --no-coverage

database:
	php bin/console doctrine:database:drop --if-exists --force --env=$(env)
	php bin/console doctrine:database:create --env=$(env)
	php bin/console doctrine:schema:update --force --env=$(env)
	php bin/console doctrine:query:sql "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));" --env=$(env)

fixtures:
	php bin/console doctrine:fixtures:load -n --env=$(env)

prepare:
	make database ENV=$(env)
	make fixtures ENV=$(env)

install:
	cp .env.dist .env.$(env).local
	sed -i -e 's/DB_USER/$(db_user)/' .env.$(env).local
	sed -i -e 's/DB_PASSWORD/$(db_password)/' .env.$(env).local
	sed -i -e 's/ENV/$(env)/' .env.$(env).local
	composer install
	make prepare env=$(env)
	yarn install
