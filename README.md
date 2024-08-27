# E-Commerce Container Build

This repository automates the build, test, and deployment processes for a WordPress-based e-commerce application. The CI pipeline is designed to run on a self-hosted runner using GitHub Actions, optimizing for cost-efficiency and performance.

## Overview

This pipeline performs the following tasks:

1. **Code Checkout**: Fetches the latest code from the `main` branch.
2. **Version Management**: Reads the application version from `manifest.json` and sets it as an environment variable.
3. **Docker Build Setup**: Configures Docker Buildx for building multi-stage Docker images.
4. **Builder Stage**: Creates a Docker image for the builder stage, including necessary dependencies.
5. **Security Scanning**: Runs a security scan on the builder image using Trivy to identify vulnerabilities.
6. **Unit Testing**: Executes PHPUnit tests within the builder Docker container.
7. **Production Stage**: Builds the final Docker image for deployment.
8. **Local Registry Push**: Tags and pushes the production Docker image to a local registry for testing.
9. **Release Tagging**: Creates a new GitHub release tagged with the application version.

## Pipeline Workflow

### Trigger

The pipeline is triggered on every push to the `main` branch.

### Jobs

#### 1. **Build**

- **Runs On**: Self-hosted runner (WSL Ubuntu 24.04)
- **Steps**:
  - **Checkout Code**: Uses `actions/checkout@v3` to fetch the latest code.
  - **Read Version**: Extracts the application version from `manifest.json`.
  - **Set Up Docker Buildx**: Initializes Docker Buildx for multi-stage builds.
  - **Build Docker Image (Builder Stage)**: Builds a Docker image using the builder stage defined in the `Dockerfile`.
  - **Security Scan with Trivy**: Scans the builder image for vulnerabilities.
  - **Run PHP Unit Tests**: Runs PHPUnit tests within the builder image to ensure code quality.
  - **Build Final Docker Image (Production Stage)**: Builds the production-ready Docker image.
  - **Tag and Push Docker Image**: Tags and pushes the Docker image to a local registry for testing.
  - **Tag Release**: Creates a GitHub release with the application version.

### Optional Steps

- **Push Docker Image to GCR**: This step can be uncommented to push the Docker image to Google Container Registry (GCR) for deployment.

## Configuration

### Secrets

- `GITHUB_TOKEN`: Automatically provided by GitHub Actions, used for authenticating API requests.
- `GCP_SA_KEY`: (Optional) Service Account key for Google Cloud, used if pushing to GCR.

### Environment Variables

- `VERSION`: Set automatically from `manifest.json`.

### Docker Registry

- **Local Registry**: The Docker image is tagged and pushed to `localhost:5000/wp-release:${VERSION}` for local testing.

## Running Tests

The pipeline runs PHPUnit tests within the Docker container. Ensure the `phpunit.xml` configuration file and test cases are present in the `tests/` directory.

```bash
docker run --rm -e HTTP_HOST=localhost -e WORDPRESS_DB_HOST=localhost -e WORDPRESS_DB_USER=root -e WORDPRESS_DB_PASSWORD=root wp-release:builder phpunit --configuration phpunit.xml
