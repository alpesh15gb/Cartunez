#!/bin/bash
# migrate.sh

echo "[ACTION] Stopping Magento Containers..."
docker compose down -v

echo "[ACTION] Backing up Magento files..."
timestamp=$(date +%Y%m%d_%H%M%S)
if [ -d "src" ]; then
    mv src "src_magento_$timestamp"
    echo "[INFO] Moved 'src' to 'src_magento_$timestamp'"
fi

mkdir src

echo "[ACTION] Creating PHP Config..."
echo "file_uploads = On
memory_limit = 512M
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 600" > php.conf.ini

echo "[ACTION] Starting WordPress Containers..."
docker compose up -d

echo "==========================================="
echo "   MIGRATION COMPLETE!"
echo "==========================================="
echo "WordPress is now running on Port 8083."
echo "Your existing Nginx proxy should work effectively immediately."
echo "Access your site at: https://cartunez.in"
