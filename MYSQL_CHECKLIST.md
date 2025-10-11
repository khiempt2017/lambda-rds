# MySQL Deployment Checklist

## ✅ Các bước cần kiểm tra

### 1. AWS Secrets Manager - Kiểm tra các secrets

Vào AWS Console → Secrets Manager → Tìm secret: `BP-api-serverless-dev-secrets`

**Các keys cần có:**

```json
{
  "MYSQL_HOST": "lambda-rds-dev-rds-instance.cnuqkicmiebq.ap-southeast-1.rds.amazonaws.com",
  "MYSQL_PORT": "3306",
  "MYSQL_DATABASE": "bp_manager",
  "MYSQL_USER": "admin",
  "MYSQL_PASSWORD": "your-password"
}
```

⚠️ **QUAN TRỌNG:**
- `MYSQL_HOST`: KHÔNG chứa port (`:3306`)
- `MYSQL_DATABASE`: Phải là `bp_manager` (tên database đã tạo trong RDS)
- `MYSQL_PORT`: Là string `"3306"`, không phải number

### 2. RDS Database - Kiểm tra database đã tạo

Connect vào RDS và kiểm tra:

```bash
# Connect to RDS
mysql -h lambda-rds-dev-rds-instance.cnuqkicmiebq.ap-southeast-1.rds.amazonaws.com \
      -u admin \
      -p

# Check databases
mysql> SHOW DATABASES;

# Expected output:
+--------------------+
| Database           |
+--------------------+
| information_schema |
| bp_manager         |  ← Phải có database này
| mysql              |
| performance_schema |
+--------------------+

# Use database
mysql> USE bp_manager;

# Check tables
mysql> SHOW TABLES;

# Expected output:
+----------------------+
| Tables_in_bp_manager |
+----------------------+
| banner_management    |
| common_settings      |
| diaries              |
| mails                |  ← Phải có bảng này
| memo_orders          |
| regular_report       |
| settings             |
| withdrawal_user      |
+----------------------+
```

### 3. Lambda VPC Configuration

Lambda phải ở trong cùng VPC với RDS:

**Trong template.yml:**

```yaml
Greetings:
  Type: AWS::Serverless::Function
  Properties:
    VpcConfig:
      SecurityGroupIds: 
        - !Ref SecurityGroupId
      SubnetIds:
        - !Ref PublicSubNetId1
        - !Ref PublicSubNetId2
```

**Khi deploy, cần truyền parameters:**

```bash
aws cloudformation deploy \
  --parameter-overrides \
    Stage=dev \
    SecurityGroupId=sg-xxxxx \
    PublicSubNetId1=subnet-xxxxx \
    PublicSubNetId2=subnet-xxxxx
```

### 4. Security Group Rules

RDS Security Group phải cho phép kết nối từ Lambda Security Group:

**Inbound Rules cho RDS Security Group:**

| Type  | Protocol | Port | Source                     |
|-------|----------|------|----------------------------|
| MySQL | TCP      | 3306 | Lambda Security Group (sg-xxxxx) |

### 5. Model Configuration

File: `src-layers/database/models/mails-mysql.model.ts`

```typescript
export class MailsMySQLModel extends MySQLModel<Mails> {
  constructor() {
    // MySQL uses simple table name (not like DynamoDB)
    const tableName = 'mails';  // ← Không có prefix BPDiary_
    super(tableName);
  }
}
```

### 6. Build & Deploy

```bash
# 1. Build
npm run build

# 2. Verify model changes compiled
cat dist/src-layers/database/models/mails-mysql.model.js | grep "tableName"

# Expected: const tableName = 'mails';

# 3. Deploy
git add .
git commit -m "Fix: Use correct table name for MySQL"
git push origin develop
```

## 🐛 Troubleshooting

### Error: "Table 'xxx.BPDiary_mails_dev' doesn't exist"

**Nguyên nhân:** Model đang dùng table name theo pattern DynamoDB

**Fix:** Đã sửa trong `mails-mysql.model.ts` - chỉ dùng `'mails'`

### Error: "Table 'lambda-rds.mails' doesn't exist"

**Nguyên nhân:** Database name sai trong Secrets Manager

**Fix:** 
1. Vào Secrets Manager
2. Sửa `MYSQL_DATABASE` thành `bp_manager`
3. Deploy lại

### Error: "getaddrinfo ENOTFOUND"

**Nguyên nhân:** Lambda không kết nối được RDS

**Possible causes:**
1. Lambda không trong VPC
2. Security Group không cho phép
3. RDS endpoint sai

**Fix:**
1. Check VPC config trong template.yml
2. Check Security Group rules
3. Verify RDS endpoint

### Error: "Access denied for user"

**Nguyên nhân:** Username/password sai

**Fix:**
1. Check RDS console → Configuration → Master username
2. Update Secrets Manager với đúng credentials

## 📝 Quick Fix Checklist

Khi deploy xong và test bị lỗi, check theo thứ tự:

- [ ] 1. Check CloudWatch Logs để xem lỗi gì
- [ ] 2. Verify Secrets Manager có đủ keys và đúng value
- [ ] 3. Check RDS database `bp_manager` đã tạo chưa
- [ ] 4. Check tables trong database đã có chưa
- [ ] 5. Check Lambda VPC config
- [ ] 6. Check Security Group rules
- [ ] 7. Rebuild và deploy lại

## 🚀 Deployment Flow

```
Code Changes
    ↓
Build (npm run build)
    ↓
Push to Git
    ↓
CodePipeline Trigger
    ↓
CodeBuild (buildspec.yml)
    ↓
Install Dependencies
    ↓
Package (CloudFormation)
    ↓
Deploy Lambda + Layer
    ↓
Lambda connects to RDS
    ↓
✅ Success!
```

## 📞 Debug Commands

### Check Lambda Environment Variables

```bash
aws lambda get-function-configuration \
  --function-name BP-api-serverless-dev-api-get-greetings \
  --query 'Environment.Variables'
```

### Test Lambda

```bash
aws lambda invoke \
  --function-name BP-api-serverless-dev-api-get-greetings \
  --payload '{}' \
  --log-type Tail \
  response.json

cat response.json
```

### View CloudWatch Logs

```bash
aws logs tail \
  /aws/lambda/BP-api-serverless-dev-api-get-greetings \
  --follow \
  --format short
```

---

**Created:** October 7, 2025  
**Project:** BP-api-serverless


