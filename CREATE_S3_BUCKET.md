# 🪣 Hướng Dẫn Tạo S3 Bucket cho Upload Image

## ⚠️ Lỗi Thường Gặp

**Lỗi**: "The bucket you are attempting to access must be addressed using the specified endpoint"

**Nguyên nhân**: 
- S3 bucket chưa được tạo
- Region của bucket không khớp với region trong code
- AWS credentials chưa được config đúng

## 🚀 Cách Khắc Phục

### Option 1: Tạo S3 Bucket Bằng AWS CLI (Khuyến nghị)

```bash
# 1. Check AWS credentials
aws sts get-caller-identity

# 2. Tạo bucket với region cụ thể (VD: ap-southeast-1 - Singapore)
aws s3api create-bucket \
  --bucket test-bp-123 \
  --region ap-southeast-1 \
  --create-bucket-configuration LocationConstraint=ap-southeast-1

# Hoặc nếu dùng us-east-1 (không cần LocationConstraint):
aws s3api create-bucket \
  --bucket test-bp-123 \
  --region us-east-1

# 3. Disable Block Public Access
aws s3api put-public-access-block \
  --bucket test-bp-123 \
  --public-access-block-configuration \
    BlockPublicAcls=false,IgnorePublicAcls=false,BlockPublicPolicy=false,RestrictPublicBuckets=false

# 4. Set Bucket Policy cho public read
cat > bucket-policy.json << 'EOF'
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "PublicReadGetObject",
      "Effect": "Allow",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": "arn:aws:s3:::test-bp-123/*"
    }
  ]
}
EOF

aws s3api put-bucket-policy \
  --bucket test-bp-123 \
  --policy file://bucket-policy.json

# 5. Set CORS
cat > cors.json << 'EOF'
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
EOF

aws s3api put-bucket-cors \
  --bucket test-bp-123 \
  --cors-configuration file://cors.json

# 6. Verify bucket
aws s3 ls s3://test-bp-123
```

### Option 2: Tạo Bucket Qua AWS Console

1. Truy cập https://s3.console.aws.amazon.com/
2. Click "Create bucket"
3. **Bucket name**: `test-bp-123` (hoặc tên bạn muốn)
4. **AWS Region**: `ap-southeast-1` (Singapore) hoặc region gần bạn
5. **Block Public Access**: Tắt hết để cho phép public read
6. Click "Create bucket"
7. Vào bucket → **Permissions** tab:
   - **Bucket Policy**: Paste policy từ trên
   - **CORS**: Paste CORS config từ trên

### Option 3: Sử dụng CloudFormation/SAM (Khi deploy)

Khi bạn deploy bằng SAM, S3 bucket sẽ được tạo tự động:

```bash
cd lambda-rds
sam build
sam deploy --guided

# Chọn region khi deploy (VD: ap-southeast-1)
# Bucket sẽ được tạo với tên: lambda-rds-dev-image-uploads
```

## 🔧 Cấu Hình Local Testing

### 1. Update `local.env.json`

```json
{
  "Parameters": {
    "S3_BUCKET_NAME": "test-bp-123",
    "S3_BUCKET_REGION": "ap-southeast-1",
    "REGION": "ap-southeast-1",
    ...
  }
}
```

### 2. Đảm Bảo AWS Credentials

```bash
# Check credentials
aws configure list

# Nếu chưa có, config:
aws configure
# AWS Access Key ID: YOUR_KEY
# AWS Secret Access Key: YOUR_SECRET
# Default region name: ap-southeast-1
# Default output format: json
```

### 3. Test Upload

```bash
# Start SAM local
cd lambda-rds
sam build
npm run start:dev

# Hoặc manual:
sam local start-api --port 3000 --env-vars local.env.json
```

## 📝 Checklist

- [ ] S3 bucket đã được tạo
- [ ] Region trong code = region của bucket
- [ ] AWS credentials đã được config
- [ ] Bucket policy cho phép public read
- [ ] CORS đã được config
- [ ] `local.env.json` có đúng bucket name và region
- [ ] SAM local đang chạy với env vars đúng

## 🧪 Test Bucket

```bash
# Test bucket access
aws s3 ls s3://test-bp-123 --region ap-southeast-1

# Upload test file
echo "test" > test.txt
aws s3 cp test.txt s3://test-bp-123/test.txt --region ap-southeast-1

# Check if file is public readable
curl https://test-bp-123.s3.ap-southeast-1.amazonaws.com/test.txt
```

## 🌍 Recommended Regions

- **ap-southeast-1** (Singapore) - Gần VN nhất
- **ap-northeast-1** (Tokyo)
- **us-east-1** (N. Virginia) - Free tier friendly
- **us-west-2** (Oregon)

## 💰 Cost Consideration

- S3 Standard: ~$0.023 per GB/month
- PUT/POST requests: $0.005 per 1,000 requests
- GET requests: $0.0004 per 1,000 requests
- Free tier: 5GB storage, 20,000 GET, 2,000 PUT (first 12 months)

## 🔒 Security Best Practices

1. ✅ Chỉ public read cho folder/files cần thiết
2. ✅ Enable versioning để recover deleted files
3. ✅ Set lifecycle policy để auto-delete old versions
4. ✅ Enable server-side encryption
5. ✅ Monitor S3 costs với AWS Cost Explorer

## 📚 References

- [AWS S3 Documentation](https://docs.aws.amazon.com/s3/)
- [S3 Bucket Policies](https://docs.aws.amazon.com/AmazonS3/latest/userguide/bucket-policies.html)
- [S3 CORS](https://docs.aws.amazon.com/AmazonS3/latest/userguide/cors.html)

