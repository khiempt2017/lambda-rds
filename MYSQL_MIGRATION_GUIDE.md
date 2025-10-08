# MySQL Migration Guide

Hướng dẫn chuyển đổi từ DynamoDB sang MySQL cho dự án BP-api-serverless

## Tổng quan

Dự án này đã được cấu hình để hỗ trợ MySQL thay vì DynamoDB. Các file sau đã được thêm/cập nhật:

### File đã cập nhật:
1. ✅ `docker-compose.yml` - Thêm MySQL service
2. ✅ `template.yml` - Thêm biến môi trường MySQL cho Lambda
3. ✅ `package.json` - Cập nhật docker network
4. ✅ `src-layers/package.json` - Thêm mysql2 dependency
5. ✅ `tools/start.sh` - Khởi động MySQL 

### File mới:
1. ✅ `local.env.json-example` - Template cho biến môi trường local
2. ✅ `src-layers/database/db/mysql-connection.ts` - MySQL connection class
3. ✅ `src-layers/database/db/index.ts` - Export database classes
4. ✅ `src-layers/database/migrations/mysql-schema.sql` - Schema SQL cho MySQL
5. ✅ `src-layers/database/models/mails-mysql.model.ts` - Ví dụ model sử dụng MySQL

## Các bước cài đặt

### 1. Cấu hình biến môi trường

Copy file example và điền thông tin:
```bash
cp local.env.json-example local.env.json
```

Cập nhật các giá trị MySQL trong `local.env.json`:
```json
{
  "Parameters": {
    "DB_TYPE": "mysql",
    "MYSQL_HOST": "mysql",
    "MYSQL_PORT": "3306",
    "MYSQL_DATABASE": "bp_manager",
    "MYSQL_USER": "bp_user",
    "MYSQL_PASSWORD": "bp_password",
    "MYSQL_ROOT_PASSWORD": "root_password"
  }
}
```

### 2. Khởi động services

Chạy script khởi động:
```bash
sh tools/start.sh
```

Hoặc khởi động thủ công:
```bash
# Khởi động MySQL
docker-compose up -d mysql

# Build project
npm run build

# Cài đặt dependencies
cd dist/src-layers/nodejs && npm install && cd -

# Start local API
npm run start:dev
```

### 3. Tạo schema MySQL

Kết nối vào MySQL container và chạy migration:
```bash
# Kết nối vào MySQL
docker exec -it mysql mysql -u bp_user -pbp_password bp_manager

# Hoặc với root user
docker exec -it mysql mysql -u root -proot_password bp_manager
```

Sau đó chạy file SQL:
```bash
# Từ host machine
docker exec -i mysql mysql -u bp_user -pbp_password bp_manager < src-layers/database/migrations/mysql-schema.sql
```

### 4. Cài đặt dependencies

```bash
# Install root dependencies
npm install

# Install layer dependencies
cd dist/src-layers/nodejs && npm install && cd -
```

## Sử dụng MySQL trong code

### Ví dụ 1: Sử dụng MySQLConnection trực tiếp

```typescript
import { MySQLConnection } from '/opt/database/db/mysql-connection';

// Lấy instance
const mysql = MySQLConnection.getInstance();

// Query data
const users = await mysql.query('SELECT * FROM mails WHERE user_id = ?', ['user123']);

// Query một record
const user = await mysql.queryOne('SELECT * FROM mails WHERE id = ?', [1]);

// Execute insert/update/delete
const result = await mysql.execute(
  'INSERT INTO mails (user_id, email) VALUES (?, ?)',
  ['user123', 'user@example.com']
);

console.log('Inserted ID:', result.insertId);

// Sử dụng transaction
await mysql.transaction(async (connection) => {
  await connection.query('INSERT INTO mails ...');
  await connection.query('UPDATE settings ...');
  // Tự động commit hoặc rollback nếu có lỗi
});
```

### Ví dụ 2: Sử dụng MySQLModel (Recommended)

```typescript
import { MailsMySQLModel } from '/opt/database/models/mails-mysql.model';

// Tạo instance
const mailsModel = new MailsMySQLModel();

// Find by user_id
const mails = await mailsModel.findByUserId('user123');

// Find one
const mail = await mailsModel.findByUserAndRecordId('user123', 1);

// Create
const result = await mailsModel.createMail({
  user_id: 'user123',
  record_id: 1,
  authkey: 'auth_key_123',
  is_self: 1,
  agree: 1,
  email: 'user@example.com',
  allow: 1,
  nick: 'User',
});

// Update
await mailsModel.updateMail('user123', 1, {
  email: 'newemail@example.com',
  allow: 1
});

// Delete
await mailsModel.deleteMail('user123', 1);

// Count
const count = await mailsModel.countByUserId('user123');
```

### Ví dụ 3: Tạo Model mới

```typescript
import { MySQLModel } from '/opt/database/db/mysql-connection';
import { RowDataPacket, ResultSetHeader } from 'mysql2/promise';

export interface MyTable extends RowDataPacket {
  id?: number;
  user_id: string;
  name: string;
  created_at?: string;
}

export class MyTableModel extends MySQLModel<MyTable> {
  constructor() {
    super('my_table');
  }

  async findByName(name: string): Promise<MyTable[]> {
    return await this.findBy({ name });
  }

  async createRecord(data: Omit<MyTable, 'id' | keyof RowDataPacket>): Promise<ResultSetHeader> {
    return await this.insert(data);
  }
}
```

## Chuyển đổi từ DynamoDB sang MySQL

### DynamoDB Code (Cũ):
```typescript
import { MailsModel } from '/opt/database/models/mails.model';

const mailsModel = new MailsModel({ user_id: 'user123' });

// Put item
await mailsModel.putItem({
  Item: {
    user_id: 'user123',
    record_id: 1,
    email: 'user@example.com'
  }
});

// Get item
const result = await mailsModel.getItem({
  Key: { user_id: 'user123', record_id: 1 }
});

// Query
const items = await mailsModel.query({
  KeyConditionExpression: 'user_id = :userId',
  ExpressionAttributeValues: { ':userId': 'user123' }
});
```

### MySQL Code (Mới):
```typescript
import { MailsMySQLModel } from '/opt/database/models/mails-mysql.model';

const mailsModel = new MailsMySQLModel();

// Create/Update item
await mailsModel.createMail({
  user_id: 'user123',
  record_id: 1,
  email: 'user@example.com'
});

// Get item
const result = await mailsModel.findByUserAndRecordId('user123', 1);

// Query
const items = await mailsModel.findByUserId('user123');
```

## Cấu trúc bảng MySQL

Xem chi tiết schema tại: `src-layers/database/migrations/mysql-schema.sql`

Các bảng chính:
- `mails` - Quản lý email
- `regular_report` - Báo cáo định kỳ
- `diaries` - Nhật ký
- `banner_management` - Quản lý banner
- `common_settings` - Cài đặt chung
- `memo_orders` - Ghi chú
- `settings` - Cài đặt người dùng
- `withdrawal_user` - Người dùng rút khỏi hệ thống

## Deployment lên AWS

### 1. Cấu hình RDS MySQL

1. Tạo RDS MySQL instance trên AWS Console
2. Lưu lại thông tin: host, port, database, username, password
3. Cấu hình Security Group để Lambda có thể kết nối

### 2. Cấu hình Secrets Manager

Thêm các secrets sau vào AWS Secrets Manager:

**For dev/stg environment:**
- Secret name: `BP-api-serverless-dev-secrets` (hoặc `-stg-secrets`)
- Keys:
  ```json
  {
    "db-type": "mysql",
    "mysql-host": "your-rds-endpoint.rds.amazonaws.com",
    "mysql-port": "3306",
    "mysql-database": "bp_manager",
    "mysql-user": "admin",
    "mysql-password": "your-password"
  }
  ```

**For common settings:**
- Secret name: `BP-api-serverless-common-secrets`
- Add keys:
  ```json
  {
    "mysql-connection-limit": "10",
    "mysql-timezone": "+09:00"
  }
  ```

### 3. Deploy

```bash
# Build
npm run build

# Deploy với SAM
sam build
sam deploy --guided
```

## Troubleshooting

### MySQL container không khởi động

```bash
# Kiểm tra logs
docker logs mysql

# Restart container
docker-compose restart mysql
```

### Không kết nối được MySQL

```bash
# Test connection
docker exec -it mysql mysqladmin ping -h localhost

# Kiểm tra port
docker ps | grep mysql
```

### Lambda không kết nối được RDS

1. Kiểm tra Security Group của RDS
2. Kiểm tra VPC configuration trong template.yml
3. Kiểm tra secrets trong AWS Secrets Manager
4. Kiểm tra CloudWatch logs

## Monitoring & Logging

MySQL connection class tự động log các lỗi qua LoggerService. Kiểm tra CloudWatch Logs để debug:

```typescript
// Custom logging
import { LoggerService } from '/opt/services';

LoggerService.logInfo('Custom log message');
LoggerService.logError('Error message');
```

## Best Practices

1. **Connection Pooling**: Sử dụng connection pool (đã được cấu hình sẵn)
2. **Transactions**: Sử dụng transactions cho các operations phức tạp
3. **Indexes**: Thêm indexes cho các queries thường xuyên
4. **Prepared Statements**: Luôn sử dụng parameterized queries (đã được xử lý trong class)
5. **Error Handling**: Bọc database calls trong try-catch

## Support

Nếu có vấn đề, kiểm tra:
1. CloudWatch Logs
2. Docker logs: `docker logs mysql`
3. Connection string trong environment variables
4. Security Group & VPC configuration

