# Test MySQL API Demo

Hướng dẫn test API MySQL với Lambda local

## Các file đã tạo

### 1. **Service Layer**
- `src-layers/services/base-mysql.service.ts` - Base service cho MySQL
- `src-layers/services/mails-mysql.service.ts` - Service cho Mails với MySQL

### 2. **Model Layer** (đã có)
- `src-layers/database/models/mails-mysql.model.ts` - Model cho Mails
- `src-layers/database/db/mysql-connection.ts` - MySQL connection class

### 3. **API Handler**
- `src/api-get-greetings/index.ts` - Demo API handler

## Cấu trúc luồng

```
API Handler (index.ts)
    ↓
MailsMySQLService (mails-mysql.service.ts)
    ↓
MailsMySQLModel (mails-mysql.model.ts)
    ↓
MySQLConnection (mysql-connection.ts)
    ↓
MySQL Database
```

Tương tự với DynamoDB:
```
API Handler
    ↓
MailsService
    ↓
MailsModel
    ↓
AWSDynamo
    ↓
DynamoDB
```

## Cách chạy test

### Bước 1: Đảm bảo MySQL đã chạy và có schema

```bash
# Khởi động MySQL (nếu chưa chạy)
docker-compose up -d mysql

# Chạy migration để tạo bảng
sh tools/migrate-mysql.sh
```

### Bước 2: Build code

```bash
npm run build
```

### Bước 3: Khởi động local API

```bash
# Cách 1: Dùng script
sh tools/start.sh

# Cách 2: Manual
npm run start:dev
```

### Bước 4: Test API

**Endpoint:** `POST http://127.0.0.1:3000/api/set_message_flg`

**Method:** POST

**Headers:**
```
Content-Type: application/json
```

**Body:** (bất kỳ, vì đã remove validation)
```json
{}
```

**Sử dụng curl:**
```bash
curl -X POST http://127.0.0.1:3000/api/set_message_flg \
  -H "Content-Type: application/json" \
  -d '{}'
```

**Sử dụng PowerShell (Windows):**
```powershell
Invoke-WebRequest -Uri "http://127.0.0.1:3000/api/set_message_flg" -Method POST -Headers @{"Content-Type"="application/json"} -Body '{}'
```

### Bước 5: Kiểm tra kết quả

**Response mẫu:**
```json
{
  "statusCode": 200,
  "body": {
    "message": "MySQL operations completed successfully",
    "data": {
      "created": {
        "insertId": 1,
        "affectedRows": 1
      },
      "allMails": [
        {
          "id": 1,
          "user_id": "demo_user_1234567890",
          "email": "demo@example.com",
          "nick": "Demo User",
          "is_self": 1,
          "created_at": "2025-10-07 12:34:56"
        }
      ],
      "totalCount": 1
    }
  }
}
```

## Kiểm tra database trực tiếp

```bash
# Kết nối vào MySQL
docker exec -it mysql mysql -u bp_user -pbp_password bp_manager

# Xem dữ liệu
mysql> SELECT * FROM mails;
mysql> SELECT COUNT(*) FROM mails;
mysql> exit
```

## Chức năng của API Demo

API này sẽ:

1. ✅ **Create** một mail record mới với:
   - `user_id`: Unique (sử dụng timestamp)
   - `record_id`: 1
   - `authkey`: Unique auth key
   - `email`: demo@example.com
   - `nick`: Demo User
   - `is_self`: 1 (self mail)

2. ✅ **Get All** mails (giới hạn 10 records)

3. ✅ Return response với:
   - Thông tin record vừa tạo (insertId, affectedRows)
   - Danh sách tất cả mails
   - Tổng số records

## Troubleshooting

### Lỗi: Cannot connect to MySQL

```bash
# Kiểm tra MySQL đang chạy
docker ps | grep mysql

# Restart MySQL
docker-compose restart mysql

# Xem logs
docker logs mysql
```

### Lỗi: Table doesn't exist

```bash
# Chạy lại migration
sh tools/migrate-mysql.sh

# Hoặc thủ công
docker exec -i mysql mysql -u bp_user -pbp_password bp_manager < src-layers/database/migrations/mysql-schema.sql
```

### Lỗi: Module not found

```bash
# Build lại
npm run build

# Cài đặt lại dependencies
cd dist/src-layers/nodejs && npm install && cd -

# Restart API
npm run start:dev
```

## Các service methods có sẵn

```typescript
const mailsService = new MailsMySQLService();

// Get methods
await mailsService.getAllMails(limit?, offset?);
await mailsService.getMailsByUserId(userId);
await mailsService.getMailByUserAndRecordId(userId, recordId);
await mailsService.getMailByAuthkey(authkey);
await mailsService.checkUserIsSelf(userId);
await mailsService.getMailsByDateRange(userId, startDate, endDate);

// Count
await mailsService.countMailsByUserId(userId);
await mailsService.getNextRecordId(userId);

// Create
await mailsService.createMail(data);

// Update
await mailsService.updateMail(userId, recordId, data);

// Delete
await mailsService.deleteMail(userId, recordId);
await mailsService.deleteAllMailsByUserId(userId);

// Batch
await mailsService.batchInsertMails(mailsArray);
```

## Next Steps

1. ✅ Tạo thêm services cho các bảng khác (diaries, settings, etc.)
2. ✅ Implement business logic phức tạp hơn
3. ✅ Thêm validation và error handling
4. ✅ Deploy lên AWS với RDS

## So sánh với DynamoDB

| Feature | DynamoDB | MySQL |
|---------|----------|-------|
| Schema | Schema-less | Schema-based |
| Query | Key-value, limited queries | Full SQL support |
| Relations | No joins | Foreign keys, joins |
| Transactions | Limited | ACID transactions |
| Indexing | GSI/LSI | Multiple indexes |
| Cost | Pay per request | Pay per instance |

## Tài liệu tham khảo

- [MySQL Migration Guide](./MYSQL_MIGRATION_GUIDE.md)
- [Quick Start](./QUICK_START_MYSQL.md)
- [MySQL Schema](./src-layers/database/migrations/mysql-schema.sql)


