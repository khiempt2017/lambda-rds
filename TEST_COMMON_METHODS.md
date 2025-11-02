# Test Common Methods Guide

Hướng dẫn test 3 common methods: `create`, `findBy`, `findAll`

## 🎯 Các Methods được test

### 1. `create(data)` - Tạo record mới
```typescript
const createResult = await mailsService.create({
  user_id: 'demo_user_123',
  record_id: 1,
  authkey: 'auth_key_123',
  is_self: 1,
  agree: 1,
  email: 'demo@example.com',
  allow: 1,
  nick: 'Demo User',
  created_at: now,
  updated_at: now,
} as any);
```

### 2. `findBy(conditions)` - Tìm records theo điều kiện
```typescript
// Single condition
const mails = await mailsService.findBy({ user_id: 'demo_user_123' });

// Multiple conditions
const selfMails = await mailsService.findBy({ 
  user_id: 'demo_user_123', 
  is_self: 1 
});
```

### 3. `findAll(limit?, offset?)` - Lấy tất cả records
```typescript
// Lấy tất cả
const allMails = await mailsService.findAll();

// Với pagination
const first10Mails = await mailsService.findAll(10, 0);
```

## 🚀 Cách test

### Bước 1: Build project
```bash
npm run build
```

### Bước 2: Khởi động MySQL (nếu chưa chạy)
```bash
docker-compose up -d mysql
sh tools/migrate-mysql.sh
```

### Bước 3: Start API
```bash
sh tools/start.sh

# Hoặc manual
npm run start:dev
```

### Bước 4: Test API endpoint

**Endpoint:** `POST http://127.0.0.1:3000/api/set_message_flg`

**Sử dụng curl:**
```bash
curl -X POST http://127.0.0.1:3000/api/set_message_flg \
  -H "Content-Type: application/json" \
  -d '{}'
```

**Sử dụng PowerShell:**
```powershell
Invoke-WebRequest -Uri "http://127.0.0.1:3000/api/set_message_flg" `
  -Method POST `
  -Headers @{"Content-Type"="application/json"} `
  -Body '{}'
```

**Sử dụng Postman/Insomnia:**
- Method: POST
- URL: http://127.0.0.1:3000/api/set_message_flg
- Headers: Content-Type: application/json
- Body: {} (empty JSON)

## 📊 Expected Response

```json
{
  "statusCode": 200,
  "body": {
    "message": "MySQL Common Methods Test Completed Successfully",
    "methods_tested": ["create", "findBy", "findAll"],
    "data": {
      "created": {
        "insertId": 1,
        "affectedRows": 1,
        "user_id": "demo_user_1733123456789"
      },
      "findByUser": {
        "count": 1,
        "mails": [
          {
            "id": 1,
            "user_id": "demo_user_1733123456789",
            "email": "demo@example.com",
            "nick": "Demo User"
          }
        ]
      },
      "findBySelfMails": {
        "count": 1,
        "mails": [
          {
            "id": 1,
            "is_self": 1
          }
        ]
      },
      "findAll": {
        "count": 10,
        "mails": [
          {
            "id": 1,
            "user_id": "demo_user_1733123456789",
            "email": "demo@example.com",
            "created_at": "2025-10-07 12:34:56"
          },
          // ... more records
        ]
      }
    }
  }
}
```

## 📝 Chi tiết test flow

### Test 1: CREATE
```
Input:  mailsService.create({ user_id, email, ... })
Action: INSERT INTO mails (user_id, email, ...) VALUES (?, ?, ...)
Output: { insertId: 1, affectedRows: 1 }
```

### Test 2: FIND BY (Single Condition)
```
Input:  mailsService.findBy({ user_id: 'demo_user_123' })
Action: SELECT * FROM mails WHERE user_id = 'demo_user_123'
Output: [{ id: 1, user_id: 'demo_user_123', ... }]
```

### Test 3: FIND BY (Multiple Conditions)
```
Input:  mailsService.findBy({ user_id: 'demo_user_123', is_self: 1 })
Action: SELECT * FROM mails WHERE user_id = 'demo_user_123' AND is_self = 1
Output: [{ id: 1, user_id: 'demo_user_123', is_self: 1, ... }]
```

### Test 4: FIND ALL
```
Input:  mailsService.findAll(10, 0)
Action: SELECT * FROM mails LIMIT 10 OFFSET 0
Output: Array of 10 mail records
```

## 🔍 Verify trong database

```bash
# Connect to MySQL
docker exec -it mysql mysql -u my_user -pmy_db_password my_database

# Check created record
mysql> SELECT * FROM mails ORDER BY id DESC LIMIT 1;

# Count total records
mysql> SELECT COUNT(*) FROM mails;

# Find by user_id
mysql> SELECT * FROM mails WHERE user_id LIKE 'demo_user_%';

# Exit
mysql> exit
```

## 📊 Test Results Check

### Success Indicators:
✅ HTTP Status: 200
✅ `methods_tested`: ["create", "findBy", "findAll"]
✅ `created.insertId` > 0
✅ `created.affectedRows` = 1
✅ `findByUser.count` >= 1
✅ `findBySelfMails.count` >= 1
✅ `findAll.count` >= 1

### Logs to Check:
```
=== MySQL Demo: Common Methods (create, findBy, findAll) ===
1. Testing CREATE method...
✓ Created! Insert ID: 1
2. Testing FIND BY method...
✓ Found 1 mails for user: demo_user_123
3. Testing FIND BY with multiple conditions...
✓ Found 1 self mails
4. Testing FIND ALL method...
✓ Found 10 total mails (limit 10)
```

## 🐛 Troubleshooting

### Error: Cannot connect to MySQL
```bash
# Check MySQL running
docker ps | grep mysql

# Restart MySQL
docker-compose restart mysql

# Check logs
docker logs mysql
```

### Error: Table doesn't exist
```bash
# Run migration
sh tools/migrate-mysql.sh

# Verify tables
docker exec -it mysql mysql -u my_user -pmy_db_password my_database -e "SHOW TABLES;"
```

### Error: Build failed
```bash
# Clean build
rm -rf dist/

# Rebuild
npm run build

# Reinstall dependencies
cd dist/src-layers/nodejs && npm install && cd -
```

### Error: API not responding
```bash
# Check if SAM is running
ps aux | grep sam

# Kill and restart
pkill -f "sam local"
npm run start:dev
```

## 💡 Tips

1. **Mỗi lần test sẽ tạo record mới** với unique `user_id` (timestamp)
2. **Database sẽ tích lũy data** - có thể xóa test data:
   ```sql
   DELETE FROM mails WHERE user_id LIKE 'demo_user_%';
   ```
3. **Xem logs chi tiết** trong terminal để debug
4. **Test nhiều lần** để verify consistency

## 📚 Code Location

**API Handler:**
- `src/api-get-greetings/index.ts`

**Service:**
- `src-layers/services/mails-mysql.service.ts`

**Base Service:**
- `src-layers/services/base-mysql.service.ts`

**Model:**
- `src-layers/database/models/mails-mysql.model.ts`

**Connection:**
- `src-layers/database/db/mysql-connection.ts`

## 🎓 Next Steps

1. ✅ Test passed → Sử dụng cho services khác
2. ✅ Test failed → Check logs và troubleshoot
3. ✅ Muốn test thêm methods → Xem `BASE_SERVICE_USAGE.md`

---

**Created:** October 7, 2025  
**Project:** BP-api-serverless

