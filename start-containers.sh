#!/bin/bash
cd docker/
echo "Building and starting containers..."
docker-compose up -d
echo "Containers are up and running."
