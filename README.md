# E-Commerce Container Build

This repository automates the build, test, and upload processes for a WordPress-based e-commerce application. The CI pipeline is designed to run on a self-hosted runner using GitHub Actions, optimizing for cost-efficiency and performance.

## Overview

This pipeline performs the following tasks:
### ci_dev
1. **Code Checkout**: Fetches the latest code from the `development` branch.
2. **Version Management**: Reads the application version from `manifest.json` and sets it as an environment variable.
3. **Docker Build Setup**: Configures Docker Buildx for building multi-stage Docker images.
4. **Builder Stage**: Creates a Docker image for the builder stage, including necessary dependencies for automated tests.
5. **Security Scanning**: Runs a security scan on the builder image using Trivy to identify vulnerabilities.
6. **Unit Testing**: Executes PHPUnit tests within the builder Docker container.
7. **Registry Push**: Tags and pushes the production Docker image to a local registry.
8. **GCP Init**: Authenticate on GCP for deployment
9. **Get Terraform Outputs**: Read the outputs json and populate the environments variables
10. **Prepare Helm and Values**: Install helm and create the helm values.yaml with the environment variables 
11. **Login to GKE Cluster**: Login to the cluster to be able to deploy the helm chart
12. **Get chart repo and install Helm Chart**: Add the chart repo and apply the helm chart to the cluster

### ci_main
1. **Code Checkout**: Fetches the latest code from the `main` branch.
2. **Version Management**: Reads the application version from `manifest.json` and sets it as an environment variable.
3. **Docker Build Setup**: Configures Docker Buildx for building multi-stage Docker images.
4. **Builder Stage**: Creates a Docker image for the builder stage, including necessary dependencies for automated tests.
5. **Security Scanning**: Runs a security scan on the builder image using Trivy to identify vulnerabilities.
6. **Unit Testing**: Executes PHPUnit tests within the builder Docker container.
7. **Production Stage**: Builds the final Docker image for deployment without unnecessary dependencies.
8. **GCP Init**: Authenticate on GCP for container registry and deployment
9. **Registry Push**: Tags and pushes the production Docker image to a remote registry.
10. **Get Terraform Outputs**: Read the outputs json and populate the environments variables
11. **Prepare Helm and Values**: Install helm and create the helm values.yaml with the environment variables 
12. **Login to GKE Cluster**: Login to the cluster to be able to deploy the helm chart
13. **Get chart repo and install Helm Chart**: Add the chart repo and apply the helm chart to the cluster
14. **Release Tagging**: Creates a new GitHub release tagged with the application version.

## Pipeline Workflow

### Trigger

The ci_dev pipeline is triggered on every push to the `develop` branch that will build test and deploy on the dev environment.

The ci_main will trigger a more complex pipeline that will pre-build, test, build a new image without testing tools, and push to GCR Registry on every push to the`main`

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

## Configuration and requirements

### Secrets

- `GITHUB_TOKEN`: Automatically provided by GitHub Actions, used for authenticating API requests.
- `GOOGLE_APPLICATION_CREDENTIALS`:  Service Account key for Google Cloud, used if pushing images to GCR and dev deployment

How to store secrets on  GitHub repository or organization:
 - Repository Secrets: Go to your GitHub repository settings, under "Secrets and variables" > "Actions", and add your secrets there.
 - Organization Secrets: If you want to use the same secrets across multiple repositories, you can store them at the organization level under "Secrets and variables".

### Environment Variables

- `VERSION`: Set automatically from `manifest.json`.

### Docker Registry

- **Local Registry**: The Docker image is tagged and pushed to `localhost:5000/wp-release:${VERSION}` for local testing.
- **GCR Registry**: Create the GCR repository prior to use it, for example `gcr.io/abc-wordpress/` then modify the file `{repo_root}\.github\workflows\ci_main.yaml` accordingly

## Running Tests

The pipeline runs PHPUnit tests within the Docker container. Ensure the `phpunit.xml` configuration file and test cases are present in the `tests/` directory.

```
bash docker run --rm -e HTTP_HOST=localhost -e WORDPRESS_DB_HOST=localhost -e WORDPRESS_DB_USER=root -e WORDPRESS_DB_PASSWORD=root wp-release:builder phpunit --configuration phpunit.xml
```

## 
For local container registry, run the docker image locally
```
docker run -d -p 5000:5000 --name registry registry:2.7
```