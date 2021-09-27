
##
## Qualimetry
## ----------
.PHONY: checkstyle cs
checkstyle: ## PHP Checkstyle
	vendor/bin/phpcs
cs: checkstyle

.PHONY: code-beautifier cbf
code-beautifier: ## Code beautifier (Checkstyle fixer)
	vendor/bin/phpcbf
cbf: code-beautifier

.PHONY: lint-php lint-twig lint-yaml lint-xliff lint-container
lint-php: ## Linter PHP
	find config src -type f -name "*.php" -exec php -l {} \;

.PHONY: composer-validate
composer-validate: ## Validate composer.json and composer.lock
	composer validate composer.json
	symfony check:security

.PHONY: metrics
metrics: ## Build static analysis from the php in src. Repports available in ./build/index.html
	cd src && ../vendor/bin/phpmetrics --report-html=../build/phpmetrics .

.PHONY: phpstan
phpstan: ## Launch PHP Static Analysis
	vendor/bin/phpstan analyse src tests --level=6 -c phpstan.neon

.PHONY: qualimetry qa
qualimetry: checkstyle lint-php cpd composer-validate metrics phpstan ## Launch all qualimetry rules
qa: qualimetry

.PHONY: cpd
cpd: ## Copy paste detector
	vendor/bin/phpcpd --fuzzy src

