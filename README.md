# 🏗️ Boilerplate - Sistema de RRHH

## 📋 Descripción

Boilerplate inicial para el desarrollo del Sistema de Gestión de RRHH con Event-Driven Architecture, DDD y CQRS.

## 🚀 Instalación Rápida

### Prerrequisitos
- PHP 8.2+
- Composer
- Node.js 18+
- Docker y Docker Compose

### Configuración

```bash
# 1. Clonar y configurar
git clone <repository-url>
cd hrm-system

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
cp env.example .env
# Editar .env con configuración de BD

# 4. Iniciar servicios
docker-compose up -d

# 5. Configurar base de datos
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

# 6. Iniciar desarrollo
symfony server:start
npm run dev
```

## 📁 Estructura del Proyecto

```
src/
├── Domain/                    # Domain Layer (DDD)
│   ├── Employee/             # Employee Bounded Context
│   ├── Payroll/              # Payroll Bounded Context
│   ├── Vacation/             # Vacation Bounded Context
│   └── Shared/               # Shared Kernel
├── Application/              # Application Layer
│   ├── Command/              # Command Handlers (CQRS)
│   ├── Query/                # Query Handlers (CQRS)
│   └── Event/                # Event Handlers
├── Infrastructure/           # Infrastructure Layer
│   ├── Persistence/          # Repositories
│   ├── Messaging/            # Event Bus
│   └── External/             # External Services
└── UI/                       # User Interface Layer
    ├── API/                  # REST Controllers
    └── Web/                  # Web Controllers

assets/                       # Frontend (Vue.js)
├── components/               # Vue Components
├── stores/                   # Pinia Stores
├── services/                 # API Services
└── views/                    # Vue Views
```

## 🔧 Herramientas de Desarrollo

### Backend
```bash
# Análisis estático
composer stan

# Estilo de código
composer cs:check
composer cs:fix

# Tests
composer test
composer test:coverage
```

### Frontend
```bash
# Análisis de código
npm run lint

# Formateo
npm run format

# Tests
npm run test
npm run test:coverage
```

## 📊 Servicios Disponibles

| Servicio | Puerto | Descripción |
|----------|--------|-------------|
| Symfony | 8000 | Backend API |
| Vue.js | 3000 | Frontend |
| MySQL | 3306 | Base de datos |
| PostgreSQL | 5432 | Base de datos (alternativa) |
| Redis | 6379 | Caché y sesiones |
| RabbitMQ | 5672 | Mensajería (Event-Driven) |
| MailHog | 8025 | Testing de emails |
| Elasticsearch | 9200 | Event Store |

## 🎯 Próximos Pasos

1. **Implementar Domain Entities** en `src/Domain/`
2. **Crear Value Objects** para validaciones
3. **Implementar Domain Events** para cambios de estado
4. **Configurar CQRS** con Command/Query Handlers
5. **Desarrollar APIs REST** en `src/UI/API/`
6. **Crear componentes Vue.js** en `assets/components/`
7. **Implementar tests** en `tests/`

## 📚 Recursos

- [Symfony Documentation](https://symfony.com/doc/current/)
- [Vue.js 3 Guide](https://vuejs.org/guide/)
- [Domain-Driven Design](https://martinfowler.com/bliki/DomainDrivenDesign.html)
- [Event-Driven Architecture](https://martinfowler.com/articles/201701-event-driven.html)
- [CQRS Pattern](https://martinfowler.com/bliki/CQRS.html)

## 🆘 Soporte

Para dudas durante el desarrollo, consultar:
- Documentación oficial de las tecnologías
- Stack Overflow para problemas específicos
- GitHub para ejemplos de código

**¡Comienza a desarrollar! 🚀** 