all: clean coverage

test: 
	vendor/bin/phpunit 

test-live-feeds:
	vendor/bin/phpunit -c phpunit-feeds.xml

coverage: 
	vendor/bin/phpunit --coverage-html=artifacts/coverage

view-coverage:
	open artifacts/coverage/index.html

clean:
	rm -rf artifacts/*
