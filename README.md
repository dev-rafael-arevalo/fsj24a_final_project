# Proyecto Final de Backend en Laravel

Este repositorio contiene el proyecto final de backend desarrollado en Laravel para el curso de Full Stack JavaScript 24A. El objetivo principal es crear una API RESTful que gestione las operaciones CRUD (Crear, Leer, Actualizar, Eliminar) de una aplicación de ejemplo.

## Integrantes del Grupo 5

- Gabriel Antonio Castillo Alegría
- Rafael Edgardo Arévalo Vanegas
- Iván Ernesto Calderón Polanco
- Josué Mauricio Benavides Batres
- Mateo Canales
- Mario José Pinto Amaya

## Requisitos

- PHP 8.0 o superior
- Composer
- MySQL o MariaDB

## Instalación

1. **Clonar el repositorio**:

   ```bash
   git clone https://github.com/dev-rafael-arevalo/fsj24a_final_project.git
   ```

2. **Instalar dependencias**:

   ```bash
   cd fsj24a_final_project
   composer install
   ```

3. **Configurar el archivo `.env`**:

   Copia el archivo `.env.example` y renómbralo a `.env`. Luego, configura las variables de entorno según tu entorno local:

   ```bash
   cp .env.example .env
   ```

   Edita el archivo `.env` y ajusta las siguientes variables:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_de_tu_base_de_datos
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

4. **Generar la clave de la aplicación**:

   ```bash
   php artisan key:generate
   ```

5. **Migrar la base de datos**:

   ```bash
   php artisan migrate
   ```

6. **Iniciar el servidor de desarrollo**:

   ```bash
   php artisan serve
   ```

   La aplicación estará disponible en `http://localhost:8000`.

## Endpoints Disponibles

La API ofrece los siguientes endpoints:

- `GET /api/items`: Obtiene una lista de todos los elementos.
- `GET /api/items/{id}`: Obtiene un elemento por su ID.
- `POST /api/items`: Crea un nuevo elemento.
- `PUT /api/items/{id}`: Actualiza un elemento existente.
- `DELETE /api/items/{id}`: Elimina un elemento por su ID.

## Contribuciones

Las contribuciones son bienvenidas. Si deseas colaborar, por favor sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una rama para tu característica (`git checkout -b feature/nueva-caracteristica`).
3. Realiza tus cambios y haz commit de ellos (`git commit -am 'Agrega nueva característica'`).
4. Haz push a la rama (`git push origin feature/nueva-caracteristica`).
5. Abre un Pull Request describiendo tus cambios.

## Licencia

Este proyecto está licenciado bajo la Licencia MIT.

## Contacto

Para más información o consultas, por favor contacta a los integrantes del Grupo 5:

- Gabriel Antonio Castillo Alegría
- Rafael Edgardo Arévalo Vanegas
- Iván Ernesto Calderón Polanco
- Josué Mauricio Benavides Batres
- Mateo Canales
- Mario José Pinto Amaya

¡Gracias por visitar nuestro proyecto! 
