# D-Card-task

Test task.
Parsing products from book shop links

## Requirements

### Local Installation
- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Symfony CLI

### Docker Installation
- Docker
- Docker Compose

## Installation

### Local Setup

1. Clone the repository:
```bash
git clone https://github.com/AntinDehoda/d-card-task.git
cd d-card-task
```
2. Install PHP dependencies:
```bash
composer install
```

3. Create and configure your `.env.local` file:
```bash
cp .env .env.local
```
Configure your database connection in `.env.local`:
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/database_name"
```

4. Create database and run migrations:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```


5. Start local server:
```bash
symfony server:start
```

Your application should now be running at `http://localhost:8000`
## Usage
Commands:
```bash
php bin/console app:parse-links
```
API endpoints:
- Get all products from database

http://localhost:8000/api/get-products

### Docker Setup

1. Clone the repository:
```bash
git clone https://github.com/AntinDehoda/d-card-task.git
cd https://github.com/AntinDehoda/d-card-task.git
```

2. Build and start containers:
```bash
docker-compose up -d --build
```

3. Set up the database:
```bash
docker-compose exec php php bin/console doctrine:migrations:migrate
```

The application will be available at `http://localhost:8090`

## Usage
Commands:
```bash
docker-compose exec php php bin/console app:parse-links
```
API endpoints:
- Get all products from database

http://localhost:8090/api/get-products

