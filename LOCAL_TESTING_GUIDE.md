# 🧪 Hướng Dẫn Test Local với SAM

## 📋 Yêu cầu

1. **AWS SAM CLI** đã cài đặt
2. **Docker** đang chạy
3. **AWS Credentials** được cấu hình (để upload lên S3 thật)

## 🚀 Cách Test API Upload Image Local

### Option 1: Test với SAM Local + S3 Thật (Khuyến nghị)

Đây là cách tốt nhất để test upload S3 vì SAM local sẽ dùng credentials AWS của bạn để upload lên S3 bucket thật.

```bash
# 1. Build project
cd lambda-rds
sam build

# 2. Start SAM local API
sam local start-api --port 3000

# Frontend sẽ call tới http://localhost:3000/api/upload-image
```

**Lưu ý quan trọng:**
- Bạn cần tạo S3 bucket trước khi test: `lambda-rds-dev-image-uploads` (hoặc tên theo pattern trong template.yml)
- Set environment variable `S3_BUCKET_NAME` trong file `.env` hoặc truyền vào command:

```bash
# Tạo file env.json
cat > env.json << EOF
{
  "UploadImage": {
    "S3_BUCKET_NAME": "lambda-rds-dev-image-uploads",
    "REGION": "us-east-1"
  }
}
EOF

# Start với env variables
sam local start-api --port 3000 --env-vars env.json
```

### Option 2: Test với LocalStack (S3 Local)

Nếu bạn muốn test hoàn toàn local mà không dùng AWS thật:

```bash
# 1. Install LocalStack
pip install localstack

# 2. Start LocalStack
localstack start -d

# 3. Tạo S3 bucket local
aws --endpoint-url=http://localhost:4566 s3 mb s3://lambda-rds-dev-image-uploads

# 4. Cập nhật Lambda function để dùng LocalStack endpoint
# Trong file index.ts:
# const s3Client = new S3Client({ 
#   region: 'us-east-1',
#   endpoint: 'http://localhost:4566', // LocalStack endpoint
#   forcePathStyle: true
# });

# 5. Start SAM với LocalStack
sam local start-api --port 3000 --docker-network bridge
```

### Option 3: Test trực tiếp Lambda function (không qua API Gateway)

```bash
# Tạo file test event
cat > events/upload-image-event.json << EOF
{
  "body": "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==",
  "httpMethod": "POST",
  "headers": {
    "Content-Type": "application/json"
  }
}
EOF

# Invoke function
sam local invoke UploadImage --event events/upload-image-event.json --env-vars env.json
```

## 🔧 AWS Credentials Configuration

Để SAM local có thể upload lên S3, bạn cần config AWS credentials:

```bash
# Option 1: AWS CLI configure
aws configure
# Nhập: Access Key ID, Secret Access Key, Region

# Option 2: Environment Variables
export AWS_ACCESS_KEY_ID=your_access_key
export AWS_SECRET_ACCESS_KEY=your_secret_key
export AWS_DEFAULT_REGION=us-east-1

# Option 3: Credentials file
# File: ~/.aws/credentials
[default]
aws_access_key_id = your_access_key
aws_secret_access_key = your_secret_key
```

## 📦 Tạo S3 Bucket cho Testing

```bash
# Tạo bucket
aws s3 mb s3://lambda-rds-dev-image-uploads --region us-east-1

# Set CORS configuration
aws s3api put-bucket-cors --bucket lambda-rds-dev-image-uploads --cors-configuration file://cors.json

# cors.json:
{
  "CORSRules": [
    {
      "AllowedOrigins": ["*"],
      "AllowedMethods": ["GET", "PUT", "POST", "DELETE", "HEAD"],
      "AllowedHeaders": ["*"],
      "MaxAgeSeconds": 3000
    }
  ]
}

# Set public read policy
aws s3api put-bucket-policy --bucket lambda-rds-dev-image-uploads --policy file://bucket-policy.json

# bucket-policy.json:
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "PublicReadGetObject",
      "Effect": "Allow",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": "arn:aws:s3:::lambda-rds-dev-image-uploads/*"
    }
  ]
}
```

## 🧪 Test với cURL

```bash
# Convert image to base64
base64 your-image.png > image-base64.txt

# Upload via API
curl -X POST http://localhost:3000/api/upload-image \
  -H "Content-Type: application/json" \
  -d @image-base64.txt
```

## 🐛 Troubleshooting

### Lỗi: "Unable to import module 'index'"
```bash
# Rebuild và restart
sam build --use-container
sam local start-api --port 3000
```

### Lỗi: "S3_BUCKET_NAME environment variable is not set"
```bash
# Kiểm tra env vars
sam local start-api --port 3000 --env-vars env.json
```

### Lỗi: "The bucket does not allow ACLs"
```bash
# Disable ACL trong S3 bucket settings hoặc xóa ACL khỏi PutObjectCommand
```

### Frontend không connect được
```bash
# Kiểm tra CORS trong API Gateway và S3
# Đảm bảo frontend đang chạy trên port khác (vd: 5173)
```

## 📝 Tips

1. **Hot Reload**: SAM local không tự reload. Sau khi sửa code, cần `sam build` lại
2. **Docker Memory**: Đảm bảo Docker có đủ RAM (ít nhất 2GB)
3. **Logs**: Xem logs trong terminal khi chạy SAM local
4. **Network**: Nếu Lambda cần internet, dùng `--docker-network bridge`

## 🎯 Best Practices

1. Test local trước khi deploy
2. Dùng separate S3 bucket cho dev/staging/prod
3. Set lifecycle policy để tự động xóa old images
4. Monitor S3 costs với AWS Cost Explorer
5. Enable S3 versioning nếu cần history

## 📚 References

- [AWS SAM CLI Docs](https://docs.aws.amazon.com/serverless-application-model/latest/developerguide/serverless-sam-cli-install.html)
- [LocalStack Docs](https://docs.localstack.cloud/overview/)
- [AWS SDK for JavaScript v3](https://docs.aws.amazon.com/AWSJavaScriptSDK/v3/latest/)

