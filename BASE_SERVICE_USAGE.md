# Base MySQL Service Usage Guide

Hướng dẫn sử dụng `BaseMySQLService` - Base class với common CRUD operations

## 📝 Tổng quan

`BaseMySQLService<T>` cung cấp các methods CRUD phổ biến mà tất cả services có thể tái sử dụng, giảm code duplicate và tăng tính nhất quán.

## 🎯 Methods có sẵn

### 📖 READ Operations

#### 1. `findAll(limit?, offset?)`
Lấy tất cả records với optional pagination.

```typescript
// Lấy tất cả
const allMails = await mailsService.findAll();

// Với pagination
const mails = await mailsService.findAll(10, 0); // 10 records, offset 0
```

#### 2. `findById(id, idColumn?)`
Tìm record theo ID.

```typescript
// Default id column = 'id'
const mail = await mailsService.findById(123);

// Custom id column
const mail = await mailsService.findById('user123', 'user_id');
```

#### 3. `findBy(conditions, limit?, offset?)`
Tìm records theo điều kiện.

```typescript
// Tìm theo conditions
const mails = await mailsService.findBy({ user_id: 'user123' });

// Với multiple conditions
const mails = await mailsService.findBy({ 
  user_id: 'user123', 
  is_self: 1 
});

// Với limit
const mails = await mailsService.findBy({ user_id: 'user123' }, 10);
```

#### 4. `findOneBy(conditions)`
Tìm một record theo điều kiện.

```typescript
const mail = await mailsService.findOneBy({ 
  user_id: 'user123', 
  record_id: 1 
});
```

---

### ✏️ CREATE Operations

#### 5. `create(data)`
Tạo một record mới.

```typescript
const result = await mailsService.create({
  user_id: 'user123',
  email: 'user@example.com',
  is_self: 1,
});

console.log('Created ID:', result.insertId);
console.log('Affected rows:', result.affectedRows);
```

#### 6. `createMany(dataArray)`
Tạo nhiều records cùng lúc.

```typescript
const results = await mailsService.createMany([
  { user_id: 'user1', email: 'user1@example.com' },
  { user_id: 'user2', email: 'user2@example.com' },
]);

results.forEach(r => console.log('ID:', r.insertId));
```

---

### 🔄 UPDATE Operations

#### 7. `updateById(id, data, idColumn?)`
Update record theo ID.

```typescript
// Default id column = 'id'
const result = await mailsService.updateById(123, {
  email: 'newemail@example.com',
  allow: 1,
});

// Custom id column
const result = await mailsService.updateById('user123', data, 'user_id');

console.log('Updated rows:', result.affectedRows);
```

#### 8. `updateBy(data, conditions)`
Update records theo điều kiện.

```typescript
const result = await mailsService.updateBy(
  { allow: 1 },
  { user_id: 'user123' }
);

console.log('Updated rows:', result.affectedRows);
```

---

### 🗑️ DELETE Operations

#### 9. `deleteById(id, idColumn?)`
Xóa record theo ID.

```typescript
// Default id column = 'id'
const result = await mailsService.deleteById(123);

// Custom id column
const result = await mailsService.deleteById('user123', 'user_id');

console.log('Deleted rows:', result.affectedRows);
```

#### 10. `deleteBy(conditions)`
Xóa records theo điều kiện.

```typescript
const result = await mailsService.deleteBy({ 
  user_id: 'user123' 
});

console.log('Deleted rows:', result.affectedRows);
```

---

### 🔢 COUNT & CHECK Operations

#### 11. `count(conditions?)`
Đếm số lượng records.

```typescript
// Đếm tất cả
const total = await mailsService.count();

// Đếm theo điều kiện
const count = await mailsService.count({ user_id: 'user123' });
```

#### 12. `exists(conditions)`
Kiểm tra record có tồn tại không.

```typescript
const exists = await mailsService.exists({ 
  user_id: 'user123',
  email: 'user@example.com' 
});

if (exists) {
  console.log('Record exists!');
}
```

---

### 🔍 CUSTOM QUERY Operations

#### 13. `query<R>(sql, params?)`
Execute custom SQL query.

```typescript
const results = await mailsService.query<any>(
  'SELECT * FROM mails WHERE created_at > ?',
  ['2025-01-01']
);
```

#### 14. `queryOne<R>(sql, params?)`
Execute query và lấy kết quả đầu tiên.

```typescript
const result = await mailsService.queryOne<any>(
  'SELECT COUNT(*) as total FROM mails WHERE user_id = ?',
  ['user123']
);

console.log('Total:', result?.total);
```

---

## 🚀 Cách tạo Service mới

### Bước 1: Tạo Model

```typescript
// src-layers/database/models/settings-mysql.model.ts
import { MySQLModel } from '../db/mysql-connection';
import { RowDataPacket } from 'mysql2/promise';

export interface SettingsData {
  id?: number;
  user_id: string;
  theme: string;
  language: string;
  created_at: string | null;
  updated_at: string | null;
}

export interface Settings extends SettingsData, RowDataPacket {}

export class SettingsMySQLModel extends MySQLModel<Settings> {
  constructor() {
    const stage = process.env.STAGE || 'local';
    const tableName = stage === 'local' ? 'settings' : `BPDiary_settings_${stage}`;
    super(tableName);
  }

  // Thêm custom methods nếu cần
  async findByUserId(userId: string): Promise<Settings | undefined> {
    return await this.findOneBy({ user_id: userId });
  }
}
```

### Bước 2: Tạo Service

```typescript
// src-layers/services/settings-mysql.service.ts
import { Settings, SettingsMySQLModel, SettingsData } from '../database/models/settings-mysql.model';
import { BaseMySQLService } from './base-mysql.service';
import { ResultSetHeader } from 'mysql2/promise';

export class SettingsMySQLService extends BaseMySQLService<Settings> {
  private settingsModel: SettingsMySQLModel;

  constructor(settingsModel: SettingsMySQLModel = new SettingsMySQLModel()) {
    super(settingsModel);
    this.settingsModel = settingsModel;
  }

  // ========== Sử dụng Base Methods ==========

  /**
   * Get settings by user ID
   * Sử dụng findOneBy từ base class
   */
  async getSettingsByUserId(userId: string): Promise<Settings | undefined> {
    return await this.findOneBy({ user_id: userId });
  }

  /**
   * Create settings
   * Sử dụng create từ base class
   */
  async createSettings(data: Omit<SettingsData, 'id'>): Promise<ResultSetHeader> {
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    return await this.create({
      ...data,
      created_at: now,
      updated_at: now,
    } as any);
  }

  /**
   * Update settings by user ID
   * Sử dụng updateBy từ base class
   */
  async updateSettingsByUserId(
    userId: string,
    data: Partial<Omit<SettingsData, 'id' | 'user_id'>>
  ): Promise<ResultSetHeader> {
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    return await this.updateBy(
      { ...data, updated_at: now } as any,
      { user_id: userId }
    );
  }

  /**
   * Delete settings by user ID
   * Sử dụng deleteBy từ base class
   */
  async deleteSettingsByUserId(userId: string): Promise<ResultSetHeader> {
    return await this.deleteBy({ user_id: userId });
  }

  /**
   * Check if user has settings
   * Sử dụng exists từ base class
   */
  async userHasSettings(userId: string): Promise<boolean> {
    return await this.exists({ user_id: userId });
  }

  // ========== Custom Business Logic ==========

  /**
   * Get or create settings
   * Kết hợp nhiều base methods
   */
  async getOrCreateSettings(userId: string): Promise<Settings> {
    // Sử dụng findOneBy từ base
    let settings = await this.findOneBy({ user_id: userId });
    
    if (!settings) {
      // Sử dụng create từ base
      const result = await this.create({
        user_id: userId,
        theme: 'light',
        language: 'ja',
        created_at: new Date().toISOString().slice(0, 19).replace('T', ' '),
        updated_at: new Date().toISOString().slice(0, 19).replace('T', ' '),
      } as any);
      
      // Lấy lại settings vừa tạo
      settings = await this.findById(result.insertId);
    }
    
    return settings!;
  }

  /**
   * Bulk update theme for multiple users
   * Sử dụng query từ base class
   */
  async bulkUpdateTheme(userIds: string[], theme: string): Promise<number> {
    const placeholders = userIds.map(() => '?').join(',');
    const sql = `
      UPDATE ${this.getTableName()}
      SET theme = ?, updated_at = NOW()
      WHERE user_id IN (${placeholders})
    `;
    
    const result = await this.query(sql, [theme, ...userIds]);
    return result.length;
  }
}
```

### Bước 3: Export Service

```typescript
// src-layers/services/index.ts
export * from './base-mysql.service';
export * from './mails-mysql.service';
export * from './settings-mysql.service'; // ← Thêm dòng này
```

### Bước 4: Export Model

```typescript
// src-layers/database/models/index.ts
export * from './mails-mysql.model';
export * from './settings-mysql.model'; // ← Thêm dòng này
```

---

## 💡 Best Practices

### ✅ DO - Nên làm

1. **Sử dụng Base Methods khi có thể**
```typescript
// ✅ Good
async getByUserId(userId: string) {
  return await this.findBy({ user_id: userId });
}
```

2. **Tạo Custom Methods cho Business Logic phức tạp**
```typescript
// ✅ Good - Kết hợp nhiều operations
async getOrCreateSettings(userId: string) {
  let settings = await this.findOneBy({ user_id: userId });
  if (!settings) {
    await this.create({ user_id: userId, ...defaults });
    settings = await this.findOneBy({ user_id: userId });
  }
  return settings;
}
```

3. **Sử dụng query() cho SQL phức tạp**
```typescript
// ✅ Good - JOIN query
async getMailsWithUserInfo(userId: string) {
  const sql = `
    SELECT m.*, u.name, u.email
    FROM ${this.getTableName()} m
    JOIN users u ON m.user_id = u.id
    WHERE m.user_id = ?
  `;
  return await this.query(sql, [userId]);
}
```

### ❌ DON'T - Không nên làm

1. **Không gọi trực tiếp model methods khi đã có base methods**
```typescript
// ❌ Bad
async getAll() {
  return await this.mailsModel.findAll(); // Nên dùng this.findAll()
}

// ✅ Good
async getAll() {
  return await this.findAll();
}
```

2. **Không duplicate logic đã có trong base**
```typescript
// ❌ Bad - Đã có this.count()
async countMails() {
  const sql = `SELECT COUNT(*) as total FROM ${this.getTableName()}`;
  const result = await this.queryOne(sql);
  return result?.total || 0;
}

// ✅ Good
async countMails() {
  return await this.count();
}
```

---

## 📊 So sánh Before/After

### Before (Không dùng Base Service)

```typescript
export class MailsService {
  constructor(private model: MailsModel) {}

  async getAll() {
    return await this.model.findAll();
  }

  async getById(id: number) {
    return await this.model.findById(id);
  }

  async create(data: any) {
    return await this.model.insert(data);
  }

  async update(id: number, data: any) {
    return await this.model.update(data, { id });
  }

  async delete(id: number) {
    return await this.model.delete({ id });
  }
}
```

### After (Dùng Base Service)

```typescript
export class MailsService extends BaseMySQLService<Mails> {
  constructor(model: MailsModel = new MailsModel()) {
    super(model);
  }

  // Tất cả methods CRUD đã có sẵn từ base!
  // Chỉ cần thêm business logic đặc thù

  async getMailsWithAttachments(userId: string) {
    // Custom business logic
    const sql = `
      SELECT m.*, a.file_name
      FROM ${this.getTableName()} m
      LEFT JOIN attachments a ON m.id = a.mail_id
      WHERE m.user_id = ?
    `;
    return await this.query(sql, [userId]);
  }
}
```

**Kết quả:**
- ✅ Giảm ~50% code
- ✅ Tăng tính nhất quán
- ✅ Dễ maintain
- ✅ Focus vào business logic

---

## 🎯 Khi nào nên tạo Custom Methods?

### Tạo custom methods khi:

1. **Business logic phức tạp**
   - Kết hợp nhiều operations
   - Có validation đặc biệt
   - Cần transaction

2. **Query phức tạp**
   - JOIN với nhiều bảng
   - Aggregate functions
   - Subqueries

3. **Domain-specific logic**
   - getOrCreateSettings()
   - calculateUserStats()
   - sendNotification()

### Sử dụng base methods khi:

1. **CRUD đơn giản**
   - findBy, findOneBy
   - create, update, delete
   - count, exists

2. **Không cần business logic**
   - Chỉ cần get/set data
   - Không có validation phức tạp

---

## 📚 Reference

**Base Service Location:**
- `src-layers/services/base-mysql.service.ts`

**Example Implementation:**
- `src-layers/services/mails-mysql.service.ts`

**Available Base Methods:**
- **Read**: `findAll`, `findById`, `findBy`, `findOneBy`
- **Create**: `create`, `createMany`
- **Update**: `updateById`, `updateBy`
- **Delete**: `deleteById`, `deleteBy`
- **Count**: `count`, `exists`
- **Custom**: `query`, `queryOne`
- **Utility**: `getTableName`

---

**Created by:** KhiemPT  
**Date:** October 7, 2025  
**Project:** BP-api-serverless

