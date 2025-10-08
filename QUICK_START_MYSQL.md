# Quick Start - MySQL Setup

Hướng dẫn nhanh để bắt đầu sử dụng MySQL trong dự án.

## Bước 1: Khởi động MySQL

```bash
# Khởi động MySQL container
docker-compose up -d mysql

# Chờ MySQL sẵn sàng (khoảng 10 giây)
```

## Bước 2: Chạy Migration

```bash
# Chạy script migration để tạo các bảng
sh tools/migrate-mysql.sh
```

Hoặc chạy thủ công:

```bash
docker exec -i mysql mysql -u bp_user -pbp_password bp_manager < src-layers/database/migrations/mysql-schema.sql
```

## Bước 3: Kiểm tra

```bash
# Kết nối vào MySQL
docker exec -it mysql mysql -u bp_user -pbp_password bp_manager

# Xem danh sách bảng
mysql> SHOW TABLES;

# Xem cấu trúc bảng mails
mysql> DESCRIBE mails;

# Thoát
mysql> exit
```

## Bước 4: Khởi động ứng dụng

```bash
# Chạy script khởi động (sẽ build và start API)
sh tools/start.sh
```

## Bước 5: Sử dụng trong code

### Cách 1: Sử dụng Model (Khuyến nghị)

```typescript
import { MailsMySQLModel } from '/opt/database/models/mails-mysql.model';

const handler = async (event: APIGatewayProxyEvent) => {
  const mailsModel = new MailsMySQLModel();
  
  // Tìm theo user_id
  const mails = await mailsModel.findByUserId('user123');
  
  // Tạo mới
  await mailsModel.createMail({
    user_id: 'user123',
    record_id: 1,
    authkey: 'auth_key',
    is_self: 1,
    agree: 1,
    email: 'user@example.com',
    allow: 1,
    nick: null,
  });
  
  return {
    statusCode: 200,
    body: JSON.stringify(mails)
  };
};
```

### Cách 2: Query trực tiếp

```typescript
import { MySQLConnection } from '/opt/database/db/mysql-connection';

const handler = async (event: APIGatewayProxyEvent) => {
  const mysql = MySQLConnection.getInstance();
  
  // Query
  const rows = await mysql.query(
    'SELECT * FROM mails WHERE user_id = ?',
    ['user123']
  );
  
  // Insert
  const result = await mysql.execute(
    'INSERT INTO mails (user_id, email) VALUES (?, ?)',
    ['user123', 'user@example.com']
  );
  
  return {
    statusCode: 200,
    body: JSON.stringify({ insertId: result.insertId })
  };
};
```

## Cấu trúc các bảng chính

| Bảng | Mô tả |
|------|-------|
| `mails` | Quản lý email người dùng |
| `regular_report` | Báo cáo định kỳ |
| `diaries` | Nhật ký sức khỏe |
| `banner_management` | Quản lý banner hiển thị |
| `common_settings` | Cài đặt chung hệ thống |
| `memo_orders` | Thứ tự ghi chú |
| `settings` | Cài đặt người dùng |
| `withdrawal_user` | Người dùng đã rút khỏi hệ thống |

## Thay đổi cấu hình MySQL

Chỉnh sửa trong `local.env.json`:

```json
{
  "Parameters": {
    "MYSQL_HOST": "mysql",
    "MYSQL_PORT": "3306",
    "MYSQL_DATABASE": "bp_manager",
    "MYSQL_USER": "bp_user",
    "MYSQL_PASSWORD": "bp_password",
    "MYSQL_CONNECTION_LIMIT": "10",
    "MYSQL_TIMEZONE": "+09:00"
  }
}
```

## Troubleshooting

### MySQL không khởi động

```bash
docker logs mysql
docker-compose restart mysql
```

### Không kết nối được MySQL

```bash
# Test connection
docker exec mysql mysqladmin ping -h localhost

# Kiểm tra port
docker ps | grep mysql
```

### Reset database

```bash
# Xóa volume và restart
docker-compose down -v
docker-compose up -d mysql
sh tools/migrate-mysql.sh
```

## Tài liệu đầy đủ

Xem chi tiết tại: [MYSQL_MIGRATION_GUIDE.md](./MYSQL_MIGRATION_GUIDE.md)

