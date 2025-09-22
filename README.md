# Laravel in Docker

This setup runs a Laravel application inside Docker with the following features:

-   Uses **PHP 8.2** with SQLite support.
-   Installs **Composer** inside the container.
-   Shares the current project folder with the container (bind mount).
-   Runs migrations and seeds the **AdminUserSeeder** automatically on container startup.
-   Exposes the Laravel application on **http://localhost:4444**.

---

## Requirements

-   Docker
-   Docker Compose

---

## Setup Instructions

1. Build and start the container:

    ```bash
    docker-compose up --build -d
    ```

2. The container will automatically:

    - Remove any existing `database/database.sqlite`
    - Create a new `database/database.sqlite`
    - Run `php artisan migrate`
    - Run `php artisan db:seed --class=AdminUserSeeder`

3. Access the app in your browser:
   👉 [http://localhost:4444](http://localhost:4444)

---

## Notes

-   The database is reset on each container startup (for development only).
-   If you want to keep data between restarts, remove the `rm` and `touch` commands from the `CMD` in the `Dockerfile`.
-   To stop the container:
    ```bash
    docker-compose down
    ```

---

## Files Included

-   **Dockerfile** → Defines the PHP + Laravel runtime
-   **docker-compose.yml** → Runs the container and binds local files
-   **README.md** → This documentation
