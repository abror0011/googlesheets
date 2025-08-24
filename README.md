# Google Sheets Integration Laravel Project

This project is built using Laravel framework for integrating with Google Sheets. The project provides capabilities to read data from Google Sheets, manage data, and store it in a database.

## About the Project

This is a web application built on Laravel 12 with the following main features:

- **Google Sheets Integration**: Reading and writing data through Google Sheets API
- **Data Management**: CRUD operations for data through DataItem model
- **Status Management**: Managing data with ALLOWED/PROHIBITED statuses
- **Queue System**: Queue worker for background jobs
- **Schedule System**: Scheduler for automated tasks
- **Docker Containerization**: Full Docker environment operation

## Technical Specifications

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL 8.0
- **Web Server**: Nginx
- **Queue**: Laravel Queue with Supervisor
- **Scheduler**: Laravel Task Scheduling
- **Google API**: Google Sheets API v2

## Running with Docker

### 1. Clone and Install the Project

```bash
# Clone the project
git clone <repository-url>
cd googlesheets
```
```bash

# Start with Docker
make install
```

### 2. Google Sheets API Configuration

1. Create a new project in Google Cloud Console
2. Enable Google Sheets API
3. Create a Service Account and download JSON credentials file
4. Place the credentials file in `storage/app/sheets/credentials.json`

### 3. Database Setup

```bash
# Run migrations
make artisan-migrate
```
```bash
# Seed data (optional)
make artisan-db-seed
```

## Usage

### Main Commands

All commands in the project are managed through Makefile:

```bash
# Show help
make help
```
```bash
# Enter Docker container
make bash
```
```bash
# View logs
make logs
```
```bash
# Start queue worker
make queue-work
```
```bash
# Read data from Google Sheets
make artisan-fetch [count]
```

### Web Interface

After starting, you can access via the following URLs:
- **Main page**: http://localhost:8084
- **Data Items**: http://localhost:8084/data-items
- **Read Google Sheets data**: http://localhost:8084/fetch
- **Enter Google Sheets URL through interface**: http://localhost:8084/data-items (located at the bottom)

### Reading Google Sheets Data

```bash
# Read all data
make artisan-fetch
```
```bash
# Read only 10 rows
make artisan-fetch 10
```

## Project Structure

```
app/
├── Clients/           # Google Sheets API client
├── Console/           # Artisan commands
├── Http/              # Controllers and Requests
├── Jobs/              # Queue jobs
├── Models/            # Eloquent models
├── Observers/         # Model observers
├── Services/          # Business logic
└── StatusEnum.php     # Status enum

database/
├── migrations/        # Database migrations
├── seeders/          # Database seeders
└── factories/        # Model factories

docker/               # Docker configurations
├── php/             # PHP Dockerfile and settings
└── nginx/           # Nginx configuration
```

## Important Files

- **GoogleSheetClient.php**: Working with Google Sheets API
- **DataItem.php**: Main data model
- **FetchGoogleSheetsData.php**: Command to read data from Google Sheets
- **DataItemController.php**: Web interface controller
- **docker-compose.yml**: Docker containers configuration

## Troubleshooting

### Common Issues

1. **Docker containers not working**:
   ```bash
   make down
   make rebuild
   ```

2. **Queue worker not working**:
   ```bash
   make queue-work
   make queue-logs
   ```

3. **Google Sheets API error**:
   - Check credentials file
   - Verify API is enabled in Google Cloud Console

### Viewing Logs

```bash
# View all logs
make logs-all
```
```bash
# Laravel logs
make logs-laravel
```
```bash
# Supervisor logs
make supervisor-logs
```

**Note**: This project is designed for development environment. Check security settings before deploying to production.
