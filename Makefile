include .env

CMD_COMPOSER:=php -d memory_limit=-1 /usr/local/bin/composer
CMD_EXEC_WEB:=docker-compose exec web bash
CMD_SYMFONY_CONSOLE:=php bin/console

help: ## Show the help command
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

web: ## Bash to the web container as the root user
	$(CMD_EXEC_WEB)

mysql: ## Navigate to the mysql container as the root user
	docker-compose exec mysql mysql -p"$(MYSQL_ROOT_PASSWORD)"

destroy: ## Destroy the containers
	docker-compose down
	docker-compose stop
	docker-compose rm -f

clean: destroy ## Destroy the containers, and build new ones
	docker-compose up --build -d
	$(CMD_EXEC_WEB) -c '\
	    $(CMD_COMPOSER) install &&\
	    $(CMD_SYMFONY_CONSOLE) app:wait-for-database-ready &&\
	    $(CMD_SYMFONY_CONSOLE) doctrine:database:create &&\
	    $(CMD_SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction\
	'

up: ## Start the containers, if you have already built them (e.g. if you restart your machine).
	docker-compose up -d

logs-web: ## View the logs for the web container
	docker-compose logs web

logs-mysql: ## View the logs for the mysql container
	docker-compose logs mysql

composer: ## Run a Composer command with argument: `make composer C='require foo/bar'`
	$(CMD_EXEC_WEB) -c '$(CMD_COMPOSER) $(C)'