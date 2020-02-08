vendor: composer.json composer.lock
	composer install -n --prefer-dist

phpcs:
	-vendor/bin/phpcs -p --colors > output/code-quality.txt

phpmd:
	-vendor/bin/phpmd src/ text phpmd.xml.dist > output/mess-detector.txt

psalm:
	-vendor/bin/psalm --config=psalm.xml.dist > output/code-analysis.txt

coverage-test:
	vendor/bin/phpunit --config phpunit.xml.dist

code-standards: phpcs phpmd psalm
