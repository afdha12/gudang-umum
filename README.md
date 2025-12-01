# gudang-umum

## Overview

This repository contains the codebase for a project, built using a combination of modern web technologies. The project leverages JavaScript, PHP, and TypeScript for its various functionalities, along with Docker for containerization, Node.js for backend execution, and Tailwind CSS for frontend styling.

## Key Features & Benefits

-   **Modern Tech Stack:** Utilizes JavaScript, PHP, TypeScript, Node.js, Tailwind CSS, and Docker for a robust and scalable architecture.
-   **Containerized Deployment:** Docker support ensures consistent deployment across different environments.
-   **Export Functionality:** Provides exporting capabilities for Monthly User Demand, Stationery, and User Demand Sheet data.
-   **Admin Panel:** Includes an admin panel for managing the application's various components.

## Prerequisites & Dependencies

Before setting up the project, ensure you have the following installed:

-   **Docker:** Required for containerization. Installation instructions can be found [here](https://docs.docker.com/get-docker/).
-   **Node.js:** Needed for managing JavaScript dependencies and potentially running frontend builds.  Installation instructions can be found [here](https://nodejs.org/).
-   **PHP:**  Required for the backend, version 8.3 or higher is recommended. Installation instructions can be found [here](https://www.php.net/manual/en/install.php).
-   **Composer:** A PHP dependency manager.  Installation instructions can be found [here](https://getcomposer.org/).
-   **Git:** For version control. Installation instructions can be found [here](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git).

## Installation & Setup Instructions

1.  **Clone the repository:**

    ```bash
    git clone git@github.com:afdha12/gudang-umum.git
    cd gudang-umum
    ```

2.  **Install PHP Dependencies:**

    ```bash
    composer install
    ```

3.  **Install JavaScript Dependencies:**

    ```bash
    npm install
    # or
    yarn install
    ```

4.  **Configure Environment Variables:**

    -   Copy `.env.example` to `.env` and update the necessary configurations (database connection, application URL, etc.).

        ```bash
        cp .env.example .env
        # Edit .env file
        nano .env
        ```

5.  **Generate Application Key:**

    ```bash
    php artisan key:generate
    ```

6.  **Database Setup:**

    -   Create a database as specified in your `.env` file.
    -   Run database migrations:

        ```bash
        php artisan migrate
        ```

    -   Optionally, seed the database with initial data:

        ```bash
        php artisan db:seed
        ```

7.  **Build Frontend Assets:**

    ```bash
    npm run build
    # or
    yarn build
    ```

8.  **Docker Setup (Optional):**

    -   Build the Docker image:

        ```bash
        docker build -t gudang-umum .
        ```

    -   Run the Docker container:

        ```bash
        docker run -p 8000:8000 gudang-umum
        ```

        *Note: Adjust the port mapping as needed.*

## Usage Examples & API Documentation

-   **Running the Application:** After setup, the application can be run using PHP's built-in server (not recommended for production):

    ```bash
    php artisan serve
    ```

    Alternatively, use a web server like Apache or Nginx.  If using Docker, the application will be served through the container.

-   **Accessing the Application:** Open your web browser and navigate to the URL specified in your `.env` file (or the Docker container's port mapping, e.g., `http://localhost:8000`).

- **API Documentation:** API documentation is not currently included within this README.  It is recommended that you create a separate API documentation using tools like Swagger or similar.

## Configuration Options

The application can be configured via environment variables in the `.env` file. Key settings include:

-   `APP_NAME`:  The name of the application.
-   `APP_ENV`:  The environment (local, production, etc.).
-   `APP_DEBUG`: Enable or disable debugging mode.
-   `APP_URL`:  The URL of the application.
-   `DB_CONNECTION`: The database connection type.
-   `DB_HOST`:  The database host.
-   `DB_PORT`:  The database port.
-   `DB_DATABASE`:  The database name.
-   `DB_USERNAME`:  The database username.
-   `DB_PASSWORD`:  The database password.

## Contributing Guidelines

We welcome contributions to this project! To contribute:

1.  Fork the repository.
2.  Create a new branch for your feature or bug fix.
3.  Make your changes and commit them with clear, descriptive messages.
4.  Submit a pull request.

Please follow these guidelines:

-   Adhere to the project's coding style and conventions.
-   Include tests for new features or bug fixes.
-   Write clear and concise documentation.

## License Information

License is not specified. All rights reserved by the owner.

## Acknowledgments

-   Laravel framework.
-   Tailwind CSS.
-   The open-source community.