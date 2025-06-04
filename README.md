# Queue Processing Service

Een Laravel-gebaseerde service voor het verwerken en beheren van queue items. Deze applicatie biedt een API voor queue status monitoring en batch processing.

## Vereisten

- PHP 8.4+
- MySQL database
- Redis server
- Composer
- Node.js & NPM

## Installatie

1. Clone de repository:
```shell script
git clone [repository-url]
cd [project-directory]
```


2. Installeer PHP dependencies:
```shell script
composer install
```


3. Installeer frontend dependencies:
```shell script
npm install
```


4. Kopieer het .env voorbeeld bestand:
```shell script
cp .env.example .env
```


5. Genereer een applicatie sleutel:
```shell script
php artisan key:generate
```


6. Configureer de database en Redis verbindingen in `.env`

7. Voer de database migraties uit:
```shell script
php artisan migrate
```


## API Endpoints

### Status Checks
- `GET /api/status/redis` - Controleert Redis verbinding en queue status
- `GET /api/status/database` - Controleert database verbinding status

### Queue Processing
- `POST /api/process` - Start een batch verwerking van queue items

## Development

Start de development servers:

```shell script
# Start Laravel development server
php artisan serve

# Start Vite voor frontend development
npm run dev
```


## Tests

Voer de tests uit met:

```shell script
php artisan test
```


## Stack

- Laravel v12.17.0
- MySQL
- Redis
- TailwindCSS v4.0.0
- Vite v6.2.4
