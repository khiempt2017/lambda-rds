#!/bin/sh
set -e

# MySQL Migration Script
# This script creates the MySQL schema for the BP-api-serverless project

echo "========================================="
echo "MySQL Migration Script"
echo "========================================="

# Configuration
MYSQL_CONTAINER="mysql"
MYSQL_USER="bp_user"
MYSQL_PASSWORD="bp_password"
MYSQL_DATABASE="bp_manager"
MIGRATION_FILE="src-layers/database/migrations/mysql-schema.sql"

# Check if MySQL container is running
if ! docker ps | grep -q $MYSQL_CONTAINER; then
    echo "Error: MySQL container is not running!"
    echo "Please start MySQL first with: docker-compose up -d mysql"
    exit 1
fi

echo "Checking MySQL connection..."
until docker exec $MYSQL_CONTAINER mysqladmin ping -h localhost --silent; do
    echo "Waiting for MySQL to be ready..."
    sleep 2
done

echo "MySQL is ready!"
echo ""
echo "Running migration..."

# Run migration
docker exec -i $MYSQL_CONTAINER mysql -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < $MIGRATION_FILE

if [ $? -eq 0 ]; then
    echo ""
    echo "========================================="
    echo "Migration completed successfully!"
    echo "========================================="
    echo ""
    echo "You can now use MySQL in your application."
    echo ""
    echo "To verify, run:"
    echo "  docker exec -it mysql mysql -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE"
    echo "  Then execute: SHOW TABLES;"
    echo ""
else
    echo ""
    echo "========================================="
    echo "Migration failed!"
    echo "========================================="
    echo "Please check the error messages above."
    exit 1
fi

