all: clean coverage

test: 
	vendor/bin/phpunit  --strict -v

coverage: 
	vendor/bin/phpunit --coverage-html=artifacts/coverage

view-coverage:
	open artifacts/coverage/index.html

clean:
	rm -rf artifacts/*
