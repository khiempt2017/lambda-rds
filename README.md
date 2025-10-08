# BP-api-serverless project use AWS SAM TypeScript with DynamoDB and API Gateway

This project is built using AWS Serverless Application Model (SAM) with TypeScript. It includes integration with DynamoDB and API Gateway for the BP Diary module in the Omron Connect app.

## Project Structure

```
├── Makefile
├── README.md
├── deployment
│   ├── env.dev.json
│   ├── env.prod.json
│   └── env.stg.json
├── docker-compose.yml
├── events
│   ├── env.json
│   └── event-request-example.json
├── jest.config.ts
├── local.env.json(copy from example.env.json template)
├── package-lock.json
├── package.json
├── src
│   └── api-get-greetings
│       └── index.ts
├── src-layers
│   ├── constants
│   │   ├── index.ts
│   │   └── tables.ts
│   ├── database
│   │   ├── db
│   │   │   └── aws-dynamo.ts
│   │   ├── migrations
│   │   │   └── greetings_table.json
│   │   └── models
│   │       ├── index.ts
│   │       └── greeting.model.ts
│   ├── middlewares
│   │   ├── index.ts
│   │   └── logging-middleware.ts
│   ├── package.json
│   ├── services
│   │   ├── index.ts
│   │   ├── db.service.ts
│   │   ├── logger.service.ts
│   │   └── greeting.service.ts
│   ├── shared
│   │   ├── index.ts
│   │   ├── api-gateway.ts
│   │   ├── custom-error.ts
│   │   ├── helpers.ts
│   │   └── validator.ts
│   └── types
│       ├── index.ts
│       └── events.ts
├── template.yml
├── tests
│   └── example.test.ts
├── tools
│   ├── start.sh
│   └── migrate.sh
└── tsconfig.json
```

## Prerequisites

- [Docker](https://www.docker.com/get-started)
- [AWS SAM CLI](https://docs.aws.amazon.com/serverless-application-model/latest/developerguide/install-sam-cli.html)
- [Node.js](https://nodejs.org/) (v18.x or later)

## Setting Up the Project

### 1. Install Dependencies

#### a. For development dependencies

The development dependencies should be located in the **root** folder. To install them, run:
```sh
npm install
```

#### b. For dependencies

The dependencies should be located in the **src-layers** folder. To install them, run:
```sh
cd src-layers
npm install
```

### 2. Local Development

First, create **local.env.json** by copying **example.env.json** and replacing the variables corresponding to the local environment.

Then to start the project locally, run the following command:
```sh
sh tools/start.sh
```

### 3. Migrate Database

To apply database migrations, run the following command:

```sh
sh tools/migrate-mysql.sh
```
chạy lệnh docker exec -it mysql mysql -u bp_user -p
### 4. Install DynamoDB Client

### 6. Deployment
To deploy the application to AWS, use:
```sh
sam deploy --guided
```

### 7. Deployment via cloudformation
To deploy the application to AWS, use:

Package your lambda functions and store them safely in a S3 bucket
```sh
aws cloudformation package --template-file template.yml --s3-bucket bucket-name --output-template-file packaged-template.yml
```
Deploy a new version of your app using the artifacts the command above just generated
```sh
aws cloudformation deploy --template-file packaged-template.yml --stack-name stack-name  --capabilities CAPABILITY_NAMED_IAM
```
