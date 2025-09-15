# 🧹 Guía de Código Limpio

## 📋 Criterios de Evaluación (10% del total)

### 1. **Estructura y Organización (25%)** 
- Separación clara de responsabilidades (DDD)
- Nomenclatura consistente y descriptiva
- Organización de archivos y directorios
- Principio de responsabilidad única

### 2. **Legibilidad y Mantenibilidad (25%)**
- Código autodocumentado
- Funciones y métodos pequeños y enfocados
- Eliminación de código duplicado (DRY)
- Complejidad ciclomática baja

### 3. **Patrones y Arquitectura (25%)**
- Aplicación correcta de DDD
- Implementación de Event-Driven Architecture
- Uso apropiado de Value Objects
- Separación de comandos y consultas (CQRS)

### 4. **Testing y Calidad (25%)**
- Cobertura de tests adecuada
- Tests unitarios bien estructurados
- Tests de integración
- Tests de event handlers

## 🔧 Herramientas de Evaluación

### Backend (PHP)
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

### Frontend (Vue.js)
```bash
# Análisis de código
npm run lint

# Formateo
npm run format

# Tests
npm run test
npm run test:coverage
```

## 📊 Métricas de Calidad

### Backend
- **Complejidad Ciclomática**: < 5 (Excelente)
- **Cobertura de Tests**: > 90% (Excelente)
- **PHPStan Level**: 8 sin errores (Excelente)

### Frontend
- **Cobertura de Tests**: > 85% (Excelente)
- **ESLint Errors**: 0 errores (Excelente)

## 🎯 Ejemplos de Código Limpio

### ✅ Bueno - Value Object
```php
<?php

declare(strict_types=1);

namespace App\Domain\Employee\ValueObject;

class EmployeeSalary
{
    private function __construct(
        private readonly Money $amount
    ) {
        $this->ensureIsValidSalary($amount);
    }

    public static function fromMoney(Money $amount): self
    {
        return new self($amount);
    }

    public function value(): Money
    {
        return $this->amount;
    }

    private function ensureIsValidSalary(Money $amount): void
    {
        if ($amount->isNegative()) {
            throw new \InvalidArgumentException('Salary cannot be negative');
        }
    }
}
```

### ✅ Bueno - Domain Event
```php
<?php

declare(strict_types=1);

namespace App\Domain\Employee\Event;

use App\Domain\Shared\DomainEvent;

class EmployeeHired implements DomainEvent
{
    public function __construct(
        private readonly EmployeeId $employeeId,
        private readonly EmployeeName $name,
        private readonly \DateTimeImmutable $hiredAt
    ) {}

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->hiredAt;
    }
}
```

### ✅ Bueno - Vue Component
```vue
<template>
  <div class="employee-card">
    <h3 class="employee-name">{{ employee.name }}</h3>
    <p class="employee-email">{{ employee.email }}</p>
    <EmployeeStatus :status="employee.status" />
    <EmployeeActions 
      :employee-id="employee.id"
      @terminate="handleTerminate"
    />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import EmployeeStatus from './EmployeeStatus.vue'
import EmployeeActions from './EmployeeActions.vue'

const props = defineProps({
  employee: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['terminate'])

const handleTerminate = (employeeId) => {
  emit('terminate', employeeId)
}
</script>
```

## ❌ Ejemplos de Código a Evitar

### ❌ Malo - Función Muy Larga
```php
// ❌ Evitar: Función con demasiadas responsabilidades
public function processEmployeeData($data) {
    // Validación
    if (empty($data['name'])) throw new Exception('Name required');
    if (empty($data['email'])) throw new Exception('Email required');
    if (empty($data['salary'])) throw new Exception('Salary required');
    
    // Persistencia
    $employee = new Employee();
    $employee->setName($data['name']);
    $employee->setEmail($data['email']);
    $employee->setSalary($data['salary']);
    $this->entityManager->persist($employee);
    $this->entityManager->flush();
    
    // Notificaciones
    $this->emailService->sendWelcomeEmail($data['email']);
    $this->notificationService->notifyHR($data['name']);
    
    // Logging
    $this->logger->info('Employee created', ['id' => $employee->getId()]);
    
    return $employee;
}
```

### ❌ Malo - Componente Vue Complejo
```vue
<!-- ❌ Evitar: Componente con demasiada lógica -->
<template>
  <div>
    <div v-for="employee in employees" :key="employee.id">
      <h3>{{ employee.name }}</h3>
      <p>{{ employee.email }}</p>
      <button @click="terminateEmployee(employee.id)">Terminate</button>
      <button @click="changeSalary(employee.id)">Change Salary</button>
      <button @click="requestVacation(employee.id)">Request Vacation</button>
      <!-- Más lógica de UI... -->
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      employees: [],
      loading: false,
      error: null,
      // Muchos más datos...
    }
  },
  methods: {
    async loadEmployees() {
      // Lógica compleja de carga...
    },
    async terminateEmployee(id) {
      // Lógica compleja de terminación...
    },
    async changeSalary(id) {
      // Lógica compleja de cambio de salario...
    },
    // Muchos más métodos...
  }
}
</script>
```

## 📈 Puntuación de Código Limpio

### Excelente (9-10 puntos)
- Todas las herramientas pasan sin errores
- Cobertura de tests > 90%
- Código bien documentado y estructurado
- Patrones arquitectónicos aplicados correctamente

### Muy Bueno (7-8 puntos)
- Pocos errores en herramientas de análisis
- Cobertura de tests 80-90%
- Código bien estructurado
- Patrones aplicados con algunas mejoras

### Bueno (5-6 puntos)
- Algunos errores en herramientas de análisis
- Cobertura de tests 70-80%
- Estructura aceptable
- Patrones aplicados básicamente

### Aceptable (3-4 puntos)
- Varios errores en herramientas de análisis
- Cobertura de tests 50-70%
- Estructura mejorable
- Patrones aplicados parcialmente

### Necesita Mejora (1-2 puntos)
- Muchos errores en herramientas de análisis
- Cobertura de tests < 50%
- Estructura deficiente
- Patrones no aplicados o mal aplicados

**¡Recuerda: El código limpio es más fácil de mantener, entender y extender! 🧹✨** 