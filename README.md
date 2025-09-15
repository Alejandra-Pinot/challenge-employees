# Proyecto Symfony + Docker

Aplicación Symfony (PHP 8.2) con entorno reproducible en Docker (php-fpm, nginx y MySQL) y pipeline CI/CD en GitHub Actions.

## Stack
- **Symfony** 6/7/8 (código compatible con **PHP 8.2** dentro de contenedor)
- **Docker Compose**: servicios `php`, `web` (nginx) y `mysql`
- **MySQL** 8.x (modo desarrollo y CI)
- Herramientas de calidad:
  - **PHPUnit** (tests)
  - **PHPStan** (análisis estático)
  - **PHP-CS-Fixer** (estilo)
- Seguridad: **OWASP ZAP** (Baseline recomendado para PR)

## Requisitos
- Docker Desktop / Engine + Docker Compose

## Inicio rápido
```bash
# 1) Clonar
git clone <repo-privado>.git
cd challenge-employees

# 2) Copiar variables
cp .env.example .env

# 3) Levantar contenedores
docker compose up -d
docker compose ps

# 4) Instalar deps PHP
docker compose exec php composer install --no-progress --prefer-dist

# 5) Migraciones
docker compose exec php php bin/console doctrine:migrations:migrate -n


# 6) Correr el paquete de calidad y pruebas
docker compose exec php composer stan
docker compose exec php ./vendor/bin/php-cs-fixer fix --dry-run --diff
docker compose exec php composer test
```

## Probar en el navegador
- Abrir en el navegador http://localhost:8080/index.html

## Colección de Postman
- Hay una colección para probar los endpoints desde Postman:
  - Archivo: employees.postman_collection.json
- Importar en Postman:
  - Abrir Postman → Import
  - Upload Files y seleccionar docs/postman/collection.json