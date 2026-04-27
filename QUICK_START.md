# 🚀 Hướng Dẫn Cài Đặt Nhanh

## 📋 Điều Kiện Tiên Quyết

Đảm bảo bạn đã cài đặt:
- ✅ PHP 8.0 trở lên
- ✅ MySQL 8.0 trở lên
- ✅ Composer
- ✅ Git (tùy chọn)

## 🔧 Các Bước Cài Đặt

### Bước 1: Vào thư mục dự án
```bash
cd d:\QLTV

d:
cd QLTV
```

### Bước 2: Cài đặt dependencies Laravel
```bash
composer install
```

**Nếu gặp lỗi:**
```bash
composer dump-autoload
```

### Bước 3: Tạo file .env
File `.env` đã có sẵn. Kiểm tra các cài đặt database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_db
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 4: Tạo database MySQL
```bash
# Mở MySQL Command Line hoặc MySQL Workbench, chạy:
CREATE DATABASE library_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Bước 5: Chạy migrations
```bash
php artisan migrate
```

### Bước 6: Chạy seeders (Tạo dữ liệu mẫu)
```bash
php artisan db:seed
```

### Bước 7: Sinh App Key
```bash
php artisan key:generate
```

### Bước 8: Khởi động server
```bash
php artisan serve
```

## 🌐 Truy Cập Ứng Dụng

**URL**: http://localhost:8000

## 👤 Tài Khoản Demo

| Vai Trò | Email | Mật Khẩu |
|---------|-------|----------|
| Quản Trị Viên | admin@library.com | password123 |
| Thủ Thư | librarian@library.com | password123 |
| Thành Viên | member1@library.com | password123 |

## ✨ Khám Phá Tính Năng

### 📚 Quản Lý Sách
- Vào `Sách` → Thêm sách mới
- Tìm kiếm theo tiêu đề, tác giả
- Xem chi tiết từng cuốn sách

### 📋 Quản Lý Phiếu Mươn
- Vào `Phiếu Mương` → Tạo phiếu mười mới
- Chọn người dùng và sách
- Ghi nhận trả sách (tự động tính phí nếu quá hạn)

### 👥 Quản Lý Người Dùng
- Vào `Người Dùng` → Thêm người dùng
- Phân quyền (Thành viên, Thủ thư, Quản trị viên)
- Xem lịch sử mương của từng người

### 🤖 AI Features
- **Gợi ý sách**: API `/api/ai/recommendations/{userId}`
- **Tìm kiếm thông minh**: `POST /api/ai/search`
- **Xu hướng**: `GET /api/ai/trends`
- **Phân loại**: `POST /api/ai/categorize`

### 💬 AI Chatbot (MỚI!)
- **Trợ lý AI thông minh** - Nhấn biểu tượng 🤖 góc dưới phải
- **Tìm sách nhanh**: "Tìm sách về lập trình"
- **Gợi ý cá nhân hóa**: "Gợi ý sách cho tôi"
- **Tra cứu mượn/trả**: "Tôi đang mượn sách gì?"
- **Hướng dẫn & FAQ**: "Làm thế nào để mượn sách?"
- **Sách phổ biến**: "Sách nào được mượn nhiều nhất?"
- **Phân loại**: `POST /api/ai/categorize`

## 🐛 Xử Lý Sự Cố

### ❌ Lỗi: "SQLSTATE[HY000]"
**Giải pháp:**
```bash
php artisan migrate:refresh --seed
```

### ❌ Lỗi: "Class not found"
**Giải pháp:**
```bash
composer dump-autoload
```

### ❌ Lỗi: "Port 8000 is already in use"
**Giải pháp:**
```bash
php artisan serve --port=8001
```

### ❌ Lỗi: "Could not find driver"
**Giải pháp:**
Kiểm tra PHP cấu hình:
```bash
php -m | findstr pdo_mysql
```

Nếu không có, cài đặt PHP PDO MySQL extension.

## 📂 Cấu Trúc Thư Mục Quan Trọng

```
d:\QLTV\
├── app/
│   ├── Http/Controllers/      ← Logic xử lý
│   └── Models/                 ← Database models
├── database/
│   ├── migrations/            ← Tạo bảng
│   └── seeders/              ← Dữ liệu mẫu
├── resources/views/           ← Giao diện
├── routes/web.php            ← Định tuyến
├── .env                       ← Cấu hình
└── README.md                  ← Tài liệu đầy đủ
```

## 💡 Tips

1. **Backup database thường xuyên**
   ```bash
   mysqldump -u root library_db > backup.sql
   ```

2. **Xem logs nếu có lỗi**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Xóa cache**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

4. **Tối ưu hóa autoloader**
   ```bash
   composer dump-autoload --optimize
   ```

## 🎉 Hoàn Tất!

Bạn đã cài đặt thành công hệ thống quản lý thư viện!

Bắt đầu khám phá các tính năng:
- Quản lý sách
- Quản lý mương/trả
- Quản lý người dùng
- Sử dụng AI để gợi ý sách

---