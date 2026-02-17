#!/bin/bash

# Stop the script if any command fails
set -e

echo " Starting Deployment..."

# echo " Pulling latest image..."
# docker compose -f docker-compose.yml -f docker-compose.prod.yml pull

# Build the latest image
echo " Building image..."
docker compose -f docker-compose.yml -f docker-compose.prod.yml build

#  Maintenance Mode
echo " Putting site in maintenance mode..."
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T app php artisan down || true

# Also wait for the containers to be healthy
echo " Recreating containers..."
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --wait --remove-orphans

echo "Fixing permissions..."
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -u root -T app chown -R www-data:www-data /var/www/storage

echo "️Running Migrations..."
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T app php artisan migrate --force

# Clear and Rebuild Caches
echo " Optimizing Cache..."
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T app php artisan optimize:clear
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T app php artisan optimize
# docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T app php artisan view:cache

# Restart Queue Workers
echo "refreshing queue workers..."
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T app php artisan queue:restart

echo " Creating Storage Link..."
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T app php artisan storage:link || true

# Bring Site Back Online
echo " Bringing site back up..."
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T app php artisan up

# Cleanup Space
echo "️  Cleaning up old images..."
docker image prune -f

echo "Deployment Complete!"
