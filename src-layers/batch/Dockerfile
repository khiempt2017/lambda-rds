# Dockerfile
FROM public.ecr.aws/docker/library/node:22
ARG REGION
ARG ENVIRONMENT
ARG BASE64_API_KEY
ARG AWS_BUCKET_S3_CSV
ARG PROJECT_ID

ENV REGION=$REGION
ENV ENVIRONMENT=$ENVIRONMENT
ENV BASE64_API_KEY=$BASE64_API_KEY
ENV AWS_BUCKET_S3_CSV=$AWS_BUCKET_S3_CSV
ENV PROJECT_ID=$PROJECT_ID

# Set the working directory
WORKDIR /app
COPY . .

# Install dependencies + coreutils
RUN apt-get update && apt-get install -y coreutils && npm install

# Expose port and start the app
EXPOSE 3000
