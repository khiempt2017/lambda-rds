#!/bin/sh
set -e

PWD=`pwd`
cd `dirname $0`/..

# Start database services (DynamoDB and MySQL)
OPTS="-f docker-compose.yml"

# Start MySQL
docker-compose $OPTS up -d mysql
echo "MySQL started..."
echo "Waiting for MySQL to be ready..."
sleep 10

# Build source code
npm run build

# Install node modules
if [ ! -d "dist/src-layers/nodejs" ]; then
    mkdir dist/src-layers/nodejs
fi

if [ ! -d "dist/src/api-send_email_batch" ]; then
    mkdir dist/src/api-send_email_batch
fi

if [ ! -d "dist/src-layers/resources" ]; then
    mkdir -p dist/src-layers/resources
    cp -a src-layers/resources/* dist/src-layers/resources/
fi

# Create the 'public' directory in 'dist/src-layers' if it doesn't exist
if [ ! -d "dist/src-layers/public" ]; then
    mkdir -p dist/src-layers/public
fi

# Copy all contents from 'src-layers/public' to 'dist/src-layers/public'
cp -a src-layers/public/* dist/src-layers/public/
cp -a src-layers/package*.json dist/src-layers/nodejs
cp -a src-layers/batch/send_email_batch.js dist/src/api-send_email_batch
cd dist/src-layers/nodejs && npm install
cd -

# Start local api
npm run start:dev

cd $PWD
