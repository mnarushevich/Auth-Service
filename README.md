# Auth Service

## About The Project

Auth Service is a Laravel-based microservice responsible for user authentication and management. It provides a robust set of features including:

*   User registration and login (via email and password).
*   JWT (JSON Web Token) based authentication.
*   Retrieval of authenticated user data, including roles and permissions.
*   Password reset functionality.
*   Token verification and refresh mechanisms.
*   Role-Based Access Control (RBAC) using Spatie's Laravel Permission library.
*   Publishes `user-created` events to a Kafka topic upon new user registration.
*   Uses UUIDs for user primary keys.
*   Includes audit logging capabilities.

The service is designed to be run in a containerized environment using Docker.

## Getting Started

### Prerequisites

To run this service, you will need Docker and Docker Compose installed on your system.

*   **Docker:** [Installation Guide](https://docs.docker.com/get-docker/)
*   **Docker Compose:** [Installation Guide](https://docs.docker.com/compose/install/)

### Installation

1.  **Clone the repository:**
    ```sh
    git clone <your-repository-url>
    cd auth-service # Or your project's root directory
    ```

2.  **Environment Configuration:**
    This project uses a `.env` file for environment-specific settings. If an `.env.example` file is present, copy it to `.env`:
    ```sh
    cp .env.example .env
    ```
    Review the `.env` file and update variables as needed. Key variables include:
    *   `APP_PORT` (defaults to `8701` in `docker-compose.yml`)
    *   `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (ensure these match your desired MySQL setup or the defaults in `docker-compose.yml`)
    *   Kafka connection details (if different from defaults).

3.  **Build and Start Services:**
    Use Docker Compose to build the images and start all services:
    ```sh
    docker-compose build
    docker-compose up -d
    ```
    For newer versions of Docker Compose, you might use:
    ```sh
    docker compose build
    docker compose up -d
    ```

4.  **Application Setup:**
    Once the containers are running, execute the following commands to set up the Laravel application:
    ```sh
    docker-compose exec auth_service composer install
    docker-compose exec auth_service php artisan key:generate
    docker-compose exec auth_service php artisan migrate
    ```
    If you have database seeders, you can run them with:
    ```sh
    docker-compose exec auth_service php artisan db:seed
    ```

## Usage

Once the installation is complete and services are running:

*   **Auth Service API:** The service will be accessible at `http://localhost:${APP_PORT}` (defaulting to `http://localhost:8701`). Refer to the API documentation (if available, e.g., via a Swagger/OpenAPI endpoint) for details on available endpoints, request, and response formats. Key API endpoints include:
    *   `POST /auth/login` - User login
    *   `POST /auth/me` - Get authenticated user data
    *   (Other endpoints for registration, logout, password reset, etc.)

*   **Mailpit (Email Testing):** View emails sent by the application (e.g., password reset emails) at `http://localhost:8025`.

*   **Kafka UI:** Monitor and manage Kafka topics and messages at `http://localhost:8080`. The Kafka cluster `local` should be pre-configured, connecting to `kafka:9093`.

## Key Technologies

*   Laravel Framework (PHP)
*   Docker & Docker Compose
*   MySQL
*   Redis
*   Apache Kafka
*   JWT Authentication (Tymon/JWTAuth)
*   Role-Based Access Control (Spatie Laravel Permission)
*   Mailpit

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

## License

Distributed under the MIT License. See `LICENSE.txt` (or `LICENSE`) for more information. (You may need to create this file if it doesn't exist).
