# Product Dashboard - Backend

A RESTful API built with Laravel 12 for managing products with JWT authentication. This backend serves as the data layer for the Product Dashboard application.

## Table of Contents

- [Tech Stack](#tech-stack)
- [Features](#features)
- [Project Structure](#project-structure)
- [Prerequisites](#prerequisites)
- [Local Development Setup](#local-development-setup)
- [Environment Configuration](#environment-configuration)
- [API Endpoints](#api-endpoints)
- [Database Schema](#database-schema)
- [Key Design Decisions and Assumptions](#key-design-decisions-and-assumptions)
- [Deployment](#deployment)
- [Related Projects](#related-projects)

## Tech Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.4 | Runtime |
| Laravel | 12.x | Framework |
| PostgreSQL | 16 | Database |
| Docker | Latest | Containerization |
| JWT Auth | 2.x | Authentication |

### Dependencies

**Production Dependencies** (`composer.json`):
```json
{
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10.1",
    "tymon/jwt-auth": "^2.0"
}
```

**Development Dependencies**:
```json
{
    "fakerphp/faker": "^1.23",
    "laravel/boost": "^2.1",
    "laravel/pail": "^1.2.2",
    "laravel/pint": "^1.24",
    "laravel/sail": "^1.41",
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.6",
    "phpunit/phpunit": "^11.5.3"
}
```

## Features

- User registration and authentication with JWT tokens
- Complete CRUD operations for products
- Product filtering (search, category, price range)
- Product sorting (price ascending/descending, newest)
- Pagination support
- Docker-based development environment
- PostgreSQL database with migrations and seeders

## Project Structure

```
product-dashboard-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # Authentication logic
│   │   │   └── ProductController.php    # Product CRUD operations
│   │   ├── Requests/
│   │   │   ├── LoginUserRequest.php     # Login validation
│   │   │   ├── StoreUserRequest.php     # Registration validation
│   │   │   ├── StoreProductRequest.php  # Create product validation
│   │   │   └── UpdateProductRequest.php # Update product validation
│   │   └── Resources/
│   │       └── ProductResource.php      # Product API response formatting
│   ├── Models/
│   │   ├── Product.php                  # Product model
│   │   └── User.php                     # User model with JWT implementation
│   └── Providers/
│       └── AppServiceProvider.php
├── config/
│   └── jwt.php                          # JWT configuration
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   └── create_products_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── ProductSeeder.php            # Seeds products from FakeStore API
├── docker/
│   └── php/
│       ├── Dockerfile                   # PHP 8.4 container configuration
│       └── entrypoint.sh                # Container startup script
├── routes/
│   └── api.php                          # API route definitions
├── docker-compose.yml                   # Docker services configuration
└── composer.json
```

## Prerequisites

- Docker and Docker Compose
- Git

## Local Development Setup

### 1. Clone the Repository

```bash
git clone https://github.com/PathumSandeepa/product-dashboard-api.git
cd product-dashboard-api
```

### 2. Create Environment File

Create a `.env` file in the project root with the following content:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:wGHYl9ljGGsOv1uocwvHvW9G9YwmdMXRuVHkB0wiw1E=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=product_dashboard
DB_USERNAME=postgres
DB_PASSWORD=postgres

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

JWT_SECRET=pfuxL7afAIa2Hvji9StOnh96mZ1798ztBVUCdxMLYGPI4InYPGu9RKl4vvCaDuMQ
```

> **Note**: You can use the provided `APP_KEY` or generate a new one using `docker compose exec app php artisan key:generate`

### 3. Start Docker Containers

```bash
docker compose up
```

This will:
- Build the PHP 8.4 application container
- Start a PostgreSQL 16 database container
- Wait for the database to be ready
- Run migrations automatically
- Start the Laravel development server on port 8000

### 4. Run Migrations (if needed manually)

```bash
docker compose exec app php artisan migrate
```

### 5. Seed the Database (Optional)

To populate the database with sample products from FakeStore API:

```bash
docker compose exec app php artisan db:seed --class=ProductSeeder
```

### 6. Generate JWT Secret (if needed)

```bash
docker compose exec app php artisan jwt:secret
```

### Running Artisan Commands

All `php artisan` commands must be run inside the Docker container:

```bash
docker compose exec app php artisan <command>
```

Examples:
```bash
# Run migrations
docker compose exec app php artisan migrate

# Clear cache
docker compose exec app php artisan cache:clear

# Run tests
docker compose exec app php artisan test

# Create a new controller
docker compose exec app php artisan make:controller ExampleController
```

## Environment Configuration

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_KEY` | Application encryption key | Required |
| `DB_HOST` | Database host | `db` (Docker service name) |
| `DB_PORT` | Database port | `5432` |
| `DB_DATABASE` | Database name | `product_dashboard` |
| `DB_USERNAME` | Database username | `postgres` |
| `DB_PASSWORD` | Database password | `postgres` |
| `JWT_SECRET` | JWT signing secret | Required |

## API Endpoints

Base URL: `http://localhost:8000/api`

### Health Check

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/health` | Health check endpoint |

### Authentication

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/register` | Register a new user | No |
| POST | `/login` | Login and get JWT token | No |
| POST | `/logout` | Invalidate current token | Yes |
| POST | `/refresh` | Refresh JWT token | Yes |
| GET | `/me` | Get authenticated user | Yes |

### Products

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/products` | List all products (paginated) | Yes |
| GET | `/products/{id}` | Get a single product | Yes |
| POST | `/products` | Create a new product | Yes |
| PUT | `/products/{id}` | Update a product | Yes |
| DELETE | `/products/{id}` | Delete a product | Yes |

### Request/Response Examples

#### Register User

**Request:**
```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:**
```json
{
    "message": "User registered successfully",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "Bearer",
    "expires_in": 3600,
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

#### Login

**Request:**
```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "Bearer",
    "expires_in": 3600,
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

#### Get Products (with filters)

**Request:**
```http
GET /api/products?search=shirt&category=clothing&min_price=10&max_price=100&sort=price_asc
Authorization: Bearer <token>
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `search` | string | Search in title and description |
| `category` | string | Filter by category |
| `min_price` | number | Minimum price filter |
| `max_price` | number | Maximum price filter |
| `sort` | string | Sort order: `price_asc`, `price_desc`, `newest` |
| `page` | number | Page number for pagination |

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "title": "Product Title",
            "description": "Product description",
            "price": "29.99",
            "category": "clothing",
            "image": "https://example.com/image.jpg",
            "rating": {
                "rate": 4.5,
                "count": 120
            }
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/products?page=1",
        "last": "http://localhost:8000/api/products?page=2",
        "prev": null,
        "next": "http://localhost:8000/api/products?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 2,
        "per_page": 10,
        "to": 10,
        "total": 20
    }
}
```

#### Create Product

**Request:**
```http
POST /api/products
Authorization: Bearer <token>
Content-Type: application/json

{
    "title": "New Product",
    "description": "Product description here",
    "price": 49.99,
    "category": "electronics",
    "image": "https://example.com/image.jpg",
    "rating": {
        "rate": 4.0,
        "count": 0
    }
}
```

#### Update Product

**Request:**
```http
PUT /api/products/1
Authorization: Bearer <token>
Content-Type: application/json

{
    "title": "Updated Product Title",
    "price": 59.99
}
```

#### Delete Product

**Request:**
```http
DELETE /api/products/1
Authorization: Bearer <token>
```

**Response:**
```json
{
    "message": "Product deleted"
}
```

## Database Schema

### Users Table

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

### Products Table

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->decimal('price', 10, 2);
    $table->string('category');
    $table->string('image');
    $table->json('rating')->nullable();
    $table->timestamps();
});
```

## Key Design Decisions and Assumptions

### Architecture

1. **RESTful API Design**: The API follows REST conventions with proper HTTP methods (GET, POST, PUT, DELETE) and status codes.

2. **JWT Authentication**: Using `tymon/jwt-auth` for stateless authentication, suitable for API-only applications and mobile clients.

3. **Form Request Validation**: Validation logic is separated into dedicated Form Request classes (`StoreProductRequest`, `UpdateProductRequest`, etc.) for clean controllers.

4. **API Resources**: Using Laravel API Resources (`ProductResource`) for consistent response formatting and data transformation.

5. **Docker-First Development**: The application is designed to run in Docker containers, ensuring consistent environments across development and production.

### Security

1. **Password Hashing**: Using Laravel's built-in hashing with bcrypt (12 rounds).

2. **JWT Token Expiration**: Tokens expire after 60 minutes (configurable in `config/jwt.php`).

3. **Protected Routes**: All product endpoints require authentication via JWT middleware.

### Database

1. **PostgreSQL**: Chosen for its robust JSON support (used for product ratings) and reliability.

2. **ILIKE Search**: Using PostgreSQL's ILIKE for case-insensitive search functionality.

3. **Pagination**: Products are paginated (10 per page) to optimize performance.

### Assumptions

1. All product operations require authenticated users.
2. Product images are stored as URLs (not uploaded files).
3. Ratings are stored as JSON with `rate` and `count` fields.
4. The initial product data comes from FakeStore API via the seeder.

## Deployment

### Production Deployment on Render

The backend is deployed on [Render](https://render.com) using a custom Docker container.

**Live URL**: https://product-dashboard-api-tusj.onrender.com

> **Note**: The backend is hosted on Render's free tier, which may cause cold start delays (30-60 seconds) after periods of inactivity.

### Docker Configuration

**Dockerfile** (`docker/php/Dockerfile`):
```dockerfile
FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
```

**Entrypoint Script** (`docker/php/entrypoint.sh`):
```bash
#!/bin/bash
set -e

required_vars=("DB_HOST" "DB_PORT" "DB_DATABASE" "DB_USERNAME" "DB_PASSWORD")

echo "Checking required database environment variables..."
for var in "${required_vars[@]}"; do
  if [ -z "$(eval echo "\$${var}")" ]; then
    echo "Error: $var is not set!"
    exit 1
  fi
done

echo "Waiting for database (${DB_HOST}:${DB_PORT})..."

until php -r "
try {
    new PDO(
        'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo 'Connected!\n';
} catch (Exception \$e) {
    exit(1);
}
"; do
  echo "Database not ready yet, retrying in 2 seconds..."
  sleep 2
done

echo "Database is ready!"

php artisan config:cache
php artisan route:cache

php artisan migrate --force

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
```

**Docker Compose** (`docker-compose.yml`):
```yaml
services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    container_name: product_dashboard_app
    volumes:
      - .:/var/www/html
    ports:
      - "8000:8000"
    depends_on:
      db:
        condition: service_healthy
    env_file:
      - .env

  db:
    image: postgres:16
    container_name: product_dashboard_db
    restart: unless-stopped
    environment:
      POSTGRES_DB: ${DB_DATABASE}
      POSTGRES_USER: ${DB_USERNAME}
      POSTGRES_PASSWORD: ${DB_PASSWORD}
    ports:
      - "5432:5432"
    volumes:
      - pgdata:/var/lib/postgresql/data
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U ${DB_USERNAME}"]
      interval: 5s
      timeout: 5s
      retries: 5

volumes:
  pgdata:
```

### Render Deployment Steps

1. **Create a new Web Service** on Render and connect your GitHub repository.

2. **Configure as Docker** environment (Render auto-detects the Dockerfile).

3. **Set Environment Variables** in Render Dashboard:
   - `APP_KEY` - Laravel application key
   - `APP_ENV` - `production`
   - `DB_CONNECTION` - `pgsql`
   - `DB_HOST` - Your Render PostgreSQL internal hostname
   - `DB_PORT` - `5432`
   - `DB_DATABASE` - Your database name
   - `DB_USERNAME` - Your database username
   - `DB_PASSWORD` - Your database password
   - `JWT_SECRET` - Your JWT secret key

4. **Create a PostgreSQL 16 database** on Render and link it to your web service.

5. **Deploy** - Render will build the Docker image and run the entrypoint script.

### Deployment Features

- Automatic migrations on every deploy (`php artisan migrate --force`)
- Configuration caching for production performance
- Route caching for faster routing
- Health check endpoint at `/api/health`
- Dynamic port binding via `$PORT` environment variable

## Related Projects

### Frontend Application

The frontend for this API is built with Next.js and deployed on Vercel.

- **Repository**: https://github.com/PathumSandeepa/product-dashboard-ui
- **Live URL**: https://product-dashboard-ui-eta.vercel.app

> **Note**: The hosted frontend may experience delays when the Render backend is in cold start state (free tier limitation).

### Running the Full Stack Locally

1. **Start the Backend** (this project):
   ```bash
   git clone https://github.com/PathumSandeepa/product-dashboard-api.git
   cd product-dashboard-api
   # Create .env file as described above
   docker compose up
   ```

2. **Start the Frontend**:
   ```bash
   git clone https://github.com/PathumSandeepa/product-dashboard-ui.git
   cd product-dashboard-ui
   # Create .env file with: NEXT_PUBLIC_API_URL=http://localhost:8000
   pnpm install
   pnpm dev
   ```

3. Access the application at `http://localhost:3000`

## License

This project is for educational and demonstration purposes.
