# MySQL Implementation Summary

Tổng hợp toàn bộ implementation MySQL cho BP-api-serverless project

## 📁 Cấu trúc Files

### Database Layer
```
src-layers/database/
├── db/
│   ├── aws-dynamo.ts              # DynamoDB connection (legacy)
│   ├── mysql-connection.ts        # ✨ MySQL connection & base model
│   └── index.ts                   # Export tất cả db classes
├── models/
│   ├── mails.model.ts             # DynamoDB model (legacy)
│   ├── mails-mysql.model.ts       # ✨ MySQL model cho Mails
│   └── index.ts                   # Export tất cả models
└── migrations/
    └── mysql-schema.sql           # ✨ MySQL schema
```

### Service Layer
```
src-layers/services/
├── base.service.ts                # Base service cho DynamoDB
├── base-mysql.service.ts          # ✨ Base service cho MySQL
├── mails.service.ts               # DynamoDB service (legacy)
├── mails-mysql.service.ts         # ✨ MySQL service cho Mails
└── index.ts                       # Export tất cả services
```

### API Layer
```
src/api-get-greetings/
└── index.ts                       # ✨ Demo API với MySQL (create + get all)
```

### Configuration
```
├── docker-compose.yml             # ✨ MySQL service config
├── local.env.json                 # ✨ MySQL env variables
├── local.env.json-example         # ✨ Template
├── template.yml                   # ✨ Lambda env config
└── package.json                   # ✨ Docker network config
```

### Tools & Scripts
```
tools/
├── start.sh                       # ✨ Khởi động MySQL + API
└── migrate-mysql.sh               # ✨ Chạy MySQL migration
```

### Documentation
```
├── MYSQL_MIGRATION_GUIDE.md       # ✨ Hướng dẫn chi tiết
├── QUICK_START_MYSQL.md           # ✨ Hướng dẫn nhanh
└── TEST_MYSQL_API.md              # ✨ Test API guide
```

## 🔄 Luồng hoạt động

### Architecture Pattern: Service → Model → Connection

```
┌─────────────────────────────────────────────────────────┐
│                    API Handler                          │
│                (src/api-*/index.ts)                     │
└──────────────────┬──────────────────────────────────────┘
                   │
                   ↓
┌─────────────────────────────────────────────────────────┐
│                 Service Layer                           │
│          (src-layers/services/*.service.ts)             │
│  • Business logic                                       │
│  • Data validation                                      │
│  • Orchestration                                        │
└──────────────────┬──────────────────────────────────────┘
                   │
                   ↓
┌─────────────────────────────────────────────────────────┐
│                  Model Layer                            │
│       (src-layers/database/models/*.model.ts)           │
│  • Data structure                                       │
│  • CRUD operations                                      │
│  • Query methods                                        │
└──────────────────┬──────────────────────────────────────┘
                   │
                   ↓
┌─────────────────────────────────────────────────────────┐
│               Connection Layer                          │
│        (src-layers/database/db/*.ts)                    │
│  • Connection pooling                                   │
│  • Query execution                                      │
│  • Transaction management                               │
└──────────────────┬──────────────────────────────────────┘
                   │
                   ↓
┌─────────────────────────────────────────────────────────┐
│                  MySQL Database                         │
└─────────────────────────────────────────────────────────┘
```

## 📊 So sánh DynamoDB vs MySQL Implementation

### DynamoDB (Legacy)
```typescript
// Model
export class MailsModel extends AWSDynamo<Mails> {
  constructor(data?: Partial<Mails>) {
    super(TABLE.mails);
    this.data = { ...defaultMails, ...data };
  }
  data: Mails;
}

// Service
export class MailsService extends BaseService<Mails> {
  constructor(private mailsModel: MailsModel = new MailsModel()) {
    super(mailsModel);
  }
  
  async checkUserIsSelf(userId: string) {
    const params = {
      KeyConditionExpression: '#user_id = :user_id',
      FilterExpression: '#is_self = :is_self',
      // ...
    };
    return await this.mailsModel.query(params);
  }
}

// Usage
const service = new MailsService();
const result = await service.checkUserIsSelf('user123');
```

### MySQL (New)
```typescript
// Model
export class MailsMySQLModel extends MySQLModel<Mails> {
  constructor() {
    const stage = process.env.STAGE || 'local';
    const tableName = stage === 'local' ? 'mails' : `BPDiary_mails_${stage}`;
    super(tableName);
  }
  
  async findSelfMailsByUserId(userId: string): Promise<Mails[]> {
    return await this.findBy({ user_id: userId, is_self: 1 });
  }
}

// Service
export class MailsMySQLService extends BaseMySQLService<Mails> {
  constructor(private mailsModel: MailsMySQLModel = new MailsMySQLModel()) {
    super(mailsModel);
  }
  
  async checkUserIsSelf(userId: string) {
    const mails = await this.mailsModel.findSelfMailsByUserId(userId);
    return mails.length > 0 ? mails[0] : null;
  }
}

// Usage
const service = new MailsMySQLService();
const result = await service.checkUserIsSelf('user123');
```

## 🎯 Key Features

### 1. **Connection Pooling**
- Singleton pattern cho MySQL connection
- Configurable pool size qua environment variables
- Auto-reconnect và error handling

### 2. **Base Model Class**
```typescript
export abstract class MySQLModel<T = any> {
  // Common CRUD operations
  findById(id, idColumn?)
  findAll(limit?, offset?)
  findBy(conditions, limit?, offset?)
  findOneBy(conditions)
  insert(data)
  update(data, conditions)
  delete(conditions)
  count(conditions?)
  exists(conditions)
  query(sql, params?)
  queryOne(sql, params?)
}
```

### 3. **Transaction Support**
```typescript
const mysql = MySQLConnection.getInstance();

await mysql.transaction(async (connection) => {
  await connection.query('INSERT INTO mails ...');
  await connection.query('UPDATE settings ...');
  // Auto commit hoặc rollback
});
```

### 4. **Type Safety**
```typescript
// Separate interfaces for data và query results
export interface MailsData {
  id?: number;
  user_id: string;
  // ... other fields
}

export interface Mails extends MailsData, RowDataPacket {}
```

## 🚀 Quick Start

### 1. Setup
```bash
# Clone và cài đặt
npm install
cd dist/src-layers/nodejs && npm install && cd -
```

### 2. Khởi động MySQL
```bash
# Start MySQL
docker-compose up -d mysql

# Run migration
sh tools/migrate-mysql.sh
```

### 3. Build & Run
```bash
# Build
npm run build

# Start API
sh tools/start.sh
```

### 4. Test API
```bash
# Test với curl
curl -X POST http://127.0.0.1:3000/api/set_message_flg \
  -H "Content-Type: application/json" \
  -d '{}'

# Hoặc với PowerShell
Invoke-WebRequest -Uri "http://127.0.0.1:3000/api/set_message_flg" -Method POST
```

### 5. Verify Data
```bash
# Connect to MySQL
docker exec -it mysql mysql -u bp_user -pbp_password bp_manager

# Query
mysql> SELECT * FROM mails ORDER BY id DESC LIMIT 5;
mysql> SELECT COUNT(*) FROM mails;
```

## 📝 Code Examples

### Create Record
```typescript
const mailsService = new MailsMySQLService();

const result = await mailsService.createMail({
  user_id: 'user123',
  record_id: 1,
  authkey: 'unique_key',
  is_self: 1,
  agree: 1,
  email: 'user@example.com',
  allow: 1,
  nick: 'John',
  created_at: null,
  updated_at: null,
});

console.log('Created ID:', result.insertId);
```

### Read Records
```typescript
// Get all
const allMails = await mailsService.getAllMails(10, 0);

// Get by user
const userMails = await mailsService.getMailsByUserId('user123');

// Get by authkey
const mail = await mailsService.getMailByAuthkey('unique_key');

// Check is self
const isSelf = await mailsService.checkUserIsSelf('user123');
```

### Update Record
```typescript
await mailsService.updateMail('user123', 1, {
  email: 'newemail@example.com',
  allow: 1,
  nick: 'John Updated',
});
```

### Delete Record
```typescript
// Delete one
await mailsService.deleteMail('user123', 1);

// Delete all user mails
await mailsService.deleteAllMailsByUserId('user123');
```

### Batch Operations
```typescript
// Batch insert
const mails = [
  { user_id: 'user1', record_id: 1, authkey: 'key1', ... },
  { user_id: 'user2', record_id: 1, authkey: 'key2', ... },
];

await mailsService.batchInsertMails(mails);
```

## 🔧 Configuration

### Environment Variables
```json
{
  "DB_TYPE": "mysql",
  "MYSQL_HOST": "mysql",
  "MYSQL_PORT": "3306",
  "MYSQL_DATABASE": "bp_manager",
  "MYSQL_USER": "bp_user",
  "MYSQL_PASSWORD": "bp_password",
  "MYSQL_CONNECTION_LIMIT": "10",
  "MYSQL_TIMEZONE": "+09:00"
}
```

### Docker Compose
```yaml
services:
  mysql:
    image: mysql:8.0
    ports:
      - '3306:3306'
    environment:
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_DATABASE: bp_manager
      MYSQL_USER: bp_user
      MYSQL_PASSWORD: bp_password
```

## 📈 Benefits

### ✅ Advantages
1. **Type Safety**: Full TypeScript support
2. **Connection Pooling**: Optimized performance
3. **Transactions**: ACID compliance
4. **SQL Support**: Complex queries với joins
5. **Familiar**: Standard SQL syntax
6. **Schema**: Data integrity với constraints
7. **Migrations**: Version control cho schema

### ⚠️ Considerations
1. **Scaling**: Vertical scaling (vs DynamoDB horizontal)
2. **Cost**: Fixed cost per instance
3. **Maintenance**: Cần quản lý database
4. **Connection Limits**: Pool size limit

## 🎓 Next Steps

### 1. Migrate More Tables
- Tạo models cho: `diaries`, `settings`, `banner_management`, etc.
- Follow pattern từ `mails-mysql.model.ts`

### 2. Implement Business Logic
- Tạo services phức tạp hơn
- Add validation và error handling
- Implement caching layer

### 3. Deploy to AWS
- Setup RDS MySQL instance
- Configure VPC và Security Groups
- Update Secrets Manager
- Deploy với SAM/CloudFormation

### 4. Monitoring & Logging
- CloudWatch logs integration
- Query performance monitoring
- Connection pool metrics

## 📚 Documentation Links

- [MySQL Migration Guide](./MYSQL_MIGRATION_GUIDE.md) - Chi tiết về migration
- [Quick Start](./QUICK_START_MYSQL.md) - Hướng dẫn nhanh
- [Test API](./TEST_MYSQL_API.md) - Test guide
- [MySQL Schema](./src-layers/database/migrations/mysql-schema.sql) - Database schema

## 🤝 Contributing

Khi tạo models/services mới, follow pattern:

1. **Model**: Extend `MySQLModel<T>`
2. **Service**: Extend `BaseMySQLService<T>`
3. **Export**: Add to `index.ts`
4. **Test**: Verify CRUD operations
5. **Document**: Update README

## ✅ Checklist Migration

- [x] MySQL service trong docker-compose
- [x] Environment variables
- [x] MySQL connection class
- [x] Base model class
- [x] Mails model
- [x] Mails service  
- [x] Demo API
- [x] Migration script
- [x] Documentation
- [ ] Migrate remaining models
- [ ] Production deployment guide
- [ ] Performance optimization
- [ ] Monitoring setup

---

**Created by:** KhiemPT  
**Date:** October 7, 2025  
**Project:** BP-api-serverless


