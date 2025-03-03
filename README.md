# News Aggregator Project

This project is a full-stack news aggregator application with a Laravel backend and a React.js frontend, all running inside Docker containers. It uses a scheduled job and queue workers to fetch news from external APIs, generate API documentation using Swagger, and maintain a production-ready environment.

## Prerequisites

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Getting Started

### 1. Clone the Repository

```bash
git clone <repository-url>
cd news-aggregator# news-aggregator
```
### 2. Configure Environment Variables
Backend:
Navigate to the backend directory and copy the example environment files:

```bash
cp .env.example .env
cp .env.testing.example .env.testing
```
Then, update these files with your local settings.


### 3. Build and Start the Containers
   From the project root, run:

```bash
docker-compose up
```


### 4. Accessing the Backend Container
   Once the containers are running, you can access the backend container with:

```bash
docker-compose exec laravel sh
```

### 5. Running Application Commands
   Inside the backend container, you can run the following commands:

Fetch News Articles:
This command dispatches jobs to fetch and save articles from external sources.

```bash
php artisan news:fetch
```
Start the Queue Worker:
This command starts the queue worker to process the dispatched jobs.

```bash
php artisan queue:work --sleep=3 --tries=3
```
Alternatively, if your entrypoint or process supervisor is configured to start the queue worker automatically, this step may not be needed.