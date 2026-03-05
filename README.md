# Proyecto Laravel con Livewire

Este es un proyecto construido con [Laravel](https://laravel.com/) y [Livewire](https://livewire.laravel.com/). 
Incluye una serie de gestores y módulos administrativos para el manejo de usuarios, facturas, recibos y competiciones.

## Requisitos Previos

Para ejecutar este proyecto utilizando su configuración nativa con contenedores, necesitas:

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [WSL2](https://learn.microsoft.com/es-es/windows/wsl/install) (si estás en Windows)

## Instalación y Configuración

Este proyecto utiliza **Laravel Sail**, la herramienta oficial de Laravel para entornos de desarrollo locales con Docker.

1. **Clonar el repositorio**
   ```bash
   git clone <URL_DE_TU_REPOSITORIO>
   cd laravelsail2
   ```

2. **Copiar archivo de entorno**
   Crea una copia del archivo `.env.example` y renómbralo a `.env`.
   ```bash
   cp .env.example .env
   ```
   *Nota: Asegúrate de configurar variables personalizadas en tu `.env`, como la URL para el envío de notificaciones de WhatsApp.*

3. **Instalar dependencias de PHP (Composer)**
   Si no tienes PHP/Composer instalado localmente, puedes usar Docker para instalar las dependencias con el siguiente comando:
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php83-composer:latest \
       composer install --ignore-platform-reqs
   ```

4. **Levantar los contenedores de Sail**
   Inicia los servicios de Docker (base de datos, servidor web, etc.) en segundo plano:
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Generar la clave de la aplicación**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

6. **Ejecutar migraciones (y seeders, si los hay)**
   Esto creará las tablas necesarias en la base de datos MySQL de tu contenedor.
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

7. **Instalar y compilar archivos Frontend (Vite/Tailwind)**
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev
   ```

## Módulos Principales (Componentes Livewire)

El proyecto cuenta con varios módulos administrativos clave, desarrollados con **Livewire**:

- **`UserManager`**: Administración de usuarios, roles y permisos dentro del panel de administración.
- **`FacturasManager`**: Control de listado, creación y edición de facturas y líneas de factura (con funcionalidades como recálculo automático y envío vía WhatsApp).
- **Control de Recibos**: Gestor de recibos con modal para confirmación de borrado, cálculo automático de campos anteriores y exportación a PDF (formato A5 horizontal).
- **`CompeticionManager`**: Gestión de competiciones.

## Tecnologías Utilizadas

- **[Laravel 11.x](https://laravel.com/)**: El framework de PHP.
- **[Laravel Sail](https://laravel.com/docs/sail)**: Para la integración y despliegue local mediante Docker.
- **[Livewire 3](https://livewire.laravel.com/)**: Para la reactividad frontend usando PHP.
- **[Tailwind CSS](https://tailwindcss.com/)**: Framework CSS basado en clases utilitarias.

## Comandos Útiles (Sail)

Si no quieres escribir `./vendor/bin/sail` cada vez, puedes configurar un alias (`alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`). 

Ejemplos comunes de uso:

- Interactuar con la consola de Artisan: `sail artisan <comando>`
- Instalar un paquete PHP: `sail composer require <autor/paquete>`
- Ver registros de errores (Logs): `sail logs -f`
- Apagar el entorno: `sail down`
