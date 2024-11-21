
<p align="center"><a href="#" target="_blank"><img src="public/translator-ms.png" width="400" alt="Translator Microservice Logo"></a></p>

# Translator Microservice

Este es un microservicio de traducción diseñado para manejar artículos extensos utilizando modelos avanzados de OpenAI, como `gpt-4`. El objetivo principal es proporcionar traducciones precisas y estructuradas en múltiples idiomas, respetando la estructura JSON de entrada y salida.

## Características

- Traducción automática de artículos completos o campos específicos de JSON.
- Compatible con modelos de OpenAI que soportan grandes ventanas de contexto, ideal para artículos extensos.
- Configuración optimizada para maximizar la capacidad de tokens de los modelos.
- Fácil despliegue mediante Docker para un entorno consistente.
- Generación de respuestas estructuradas en JSON.

## Requisitos

- **PHP** >= 8.2
- **Composer**
- **Docker** (opcional pero recomendado)

## Instalación

### Localmente

1. Clona el repositorio:
   ```bash
   git clone <repo-url>
   cd translator-microservice
   ```

2. Instala las dependencias:
   ```bash
   composer install
   ```

3. Copia el archivo de configuración `.env`:
   ```bash
   cp .env.example .env
   ```

4. Configura tu clave de API de OpenAI en el archivo `.env`:
   ```env
   OPENAI_API_KEY=your_api_key_here
   ```

5. Genera la clave de aplicación:
   ```bash
   php artisan key:generate
   ```

6. Inicia el servidor:
   ```bash
   php artisan serve
   ```

### Usando Docker

1. Construye y levanta los contenedores:
   ```bash
   docker-compose up --build
   ```

2. Accede al microservicio en [http://localhost:8000](http://localhost:8000).

## Uso

### Ejemplo de Entrada

Envía un POST request a la ruta `/api/translate` con el siguiente formato:

```json
{
  "from": "es",
  "to": ["en", "fr"],
  "fields": {
    "title": "Bienvenido a Aeroméxico",
    "description": "Disfruta de una experiencia única en el cielo.",
    "footer": null
  }
}
```

### Ejemplo de Respuesta

```json
[
  {
    "language": "en",
    "fields": {
      "title": "Welcome to Aeroméxico",
      "description": "Enjoy a unique experience in the sky."
    }
  },
  {
    "language": "fr",
    "fields": {
      "title": "Bienvenue à Aeroméxico",
      "description": "Profitez d'une expérience unique dans le ciel."
    }
  }
]
```

## Arquitectura

- **Backend:** Laravel Framework.
- **Traducción:** OpenAI GPT-4.
- **Despliegue:** Docker para un entorno escalable y portátil.
- **Configuración de Tokens:** Dinámica para maximizar el uso de la ventana de contexto del modelo.

## Scripts Útiles

### Construir contenedores desde cero

```bash
docker-compose up --build
```

### Parar contenedores

```bash
docker-compose down
```

### Acceso a la línea de comandos dentro del contenedor

```bash
docker exec -it translator-app bash
```

## Contribución

¡Gracias por tu interés en contribuir! Si encuentras algún problema o tienes sugerencias para mejorar el proyecto, abre un issue o envía un pull request.

## Licencia

Este proyecto está licenciado bajo la licencia [MIT](https://opensource.org/licenses/MIT).
