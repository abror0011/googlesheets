# Docker and Laravel commands for Google Sheets Integration
CONTAINER := docker exec -it googlesheet-php
BASH := docker exec -it googlesheet-php bash
PHP := docker exec -it googlesheet-php php
COMPOSER := docker exec -it googlesheet-php composer
ARTISAN := docker exec -it googlesheet-php php artisan

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

bash: ## Bash
	$(BASH)

composer-install: ## Composer install
	$(COMPOSER) install

composer-update: ## Composer update
	$(COMPOSER) update

composer-require: ## Composer install new package
ifndef package
	$(error You must specify a package, e.g., 'make composer-require package=<laravel/package-name>')
endif
	$(COMPOSER) require $(package)

artisan-migrate: ## Run Artisan from Docker
	$(ARTISAN) migrate

artisan-db-seed: ## Artisan DB seed
	$(ARTISAN) db:seed

artisan-fetch: ## Fetch google sheets data
	$(ARTISAN) sheets:fetch $(filter-out $@,$(MAKECMDGOALS))

# Docker
install: ## Install and start the application
	docker-compose up -d --build
	@echo "Application is starting up..."
	@echo "Wait a few moments for initialization to complete"
	@echo "Access the application at: http://localhost:8084"

rebuild: ## Rebuild Docker images and start
	docker-compose down
	docker-compose build --no-cache
	docker-compose up -d
	@echo "Application rebuilt and started"
	@echo "Access the application at: http://localhost:8084"

up: ## Start all containers
	docker-compose up -d

down: ## Stop and remove all containers
	docker-compose down

restart: ## Restart all containers
	docker-compose restart

logs: ## View application logs
	docker-compose logs -f

supervisor-logs: ## View supervisor logs
	docker exec googlesheet-php tail -f /var/log/supervisor/supervisord.log

# Queue Commands
queue-work: ## Process one queue job
	docker exec googlesheet-php php artisan queue:work --once

queue-failed: ## List failed jobs
	docker exec googlesheet-php php artisan queue:failed

queue-retry: ## Retry all failed jobs
	docker exec googlesheet-php php artisan queue:retry all

queue-logs: ## View queue worker logs
	docker exec googlesheet-php tail -f /var/log/supervisor/queue-worker.log

# Schedule Commands
schedule-run: ## Run scheduled tasks manually
	docker exec googlesheet-php php artisan schedule:run

schedule-list: ## List all scheduled tasks
	docker exec googlesheet-php php artisan schedule:list

schedule-logs: ## View schedule worker logs
	docker exec googlesheet-php tail -f /var/log/supervisor/schedule-worker.log


# Utility Commands
clear-cache: ## Clear all Laravel caches
	docker exec googlesheet-php php artisan cache:clear
	docker exec googlesheet-php php artisan config:clear
	docker exec googlesheet-php php artisan route:clear
	docker exec googlesheet-php php artisan view:clear

logs-laravel: ## View Laravel logs
	docker exec googlesheet-php tail -f storage/logs/laravel.log

logs-all: ## View all logs (Laravel, Supervisor, Queue, Schedule)
	@echo "=== Laravel Logs ==="
	docker exec googlesheet-php tail -n 10 storage/logs/laravel.log
	@echo ""
	@echo "=== Supervisor Logs ==="
	docker exec googlesheet-php tail -n 10 /var/log/supervisor/supervisord.log
	@echo ""
	@echo "=== Queue Worker Logs ==="
	docker exec googlesheet-php tail -n 10 /var/log/supervisor/queue-worker.log
	@echo ""
	@echo "=== Schedule Worker Logs ==="
	docker exec googlesheet-php tail -n 10 /var/log/supervisor/schedule-worker.log
%:
	@:
