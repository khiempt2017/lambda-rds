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


# Copy all contents from 'src-layers/public' to 'dist/src-layers/public'
cp -a src-layers/public/* dist/src-layers/public/
cp -a src-layers/package*.json dist/src-layers/nodejs
cd dist/src-layers/nodejs && npm install
cd -

# Start local api
npm run start:dev

cd $PWD
