install-root-dependencies:
	composer update

install-coding-standard-dependencies:
	cd tools/php-coding-standard && composer update

install-unit-test-dependencies:
	cd tools/php-unit && composer update

install: install-root-dependencies install-coding-standard-dependencies install-unit-test-dependencies

coding-standard-fix:
	./tools/php-coding-standard/vendor/bin/phpcbf --basepath=. --standard=tools/php-coding-standard/.phpcs.xml

coding-standard-check:
	./tools/php-coding-standard/vendor/bin/phpcs --basepath=. --standard=tools/php-coding-standard/.phpcs.xml

unit-test:
	./tools/php-unit/vendor/bin/phpunit -c tools/php-unit/phpunit.xml.dist

code-coverage-html:
	XDEBUG_MODE=coverage ./tools/php-unit/vendor/bin/phpunit -c tools/php-unit/phpunit.xml.dist --coverage-html tools/php-unit/reports/codecoverage

check: coding-standard-check unit-test
