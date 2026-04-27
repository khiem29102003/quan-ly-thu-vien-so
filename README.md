# 📚 Hệ Thống Quản Lý Thư Viện - Library Management System

Một ứng dụng web hiện đại để quản lý thư viện với các tính năng AI hỗ trợ, được xây dựng bằng **Laravel, PHP, HTML, CSS và MySQL**.

## ✨ Tính Năng Chính

### 1. **Quản Lý Sách**
- ✓ Thêm, sửa, xóa sách
- ✓ Tìm kiếm sách theo tiêu đề, tác giả, ISBN
- ✓ Phân loại sách theo danh mục
- ✓ Hiển thị tình trạng sẵn có của sách
- ✓ Đánh giá sách

### 2. **Quản Lý Phiếu Mượn**
- ✓ Tạo phiếu mượn mới
- ✓ Ghi nhận trả sách
- ✓ Theo dõi thời hạn mượn (14 ngày)
- ✓ Tính toán tiền phạt quá hạn (5.000 ₫/ngày)
- ✓ Kiểm soát tình trạng phiếu mượn

### 3. **Quản Lý Người Dùng**
- ✓ Tạo tài khoản người dùng (Thành viên, Thủ Thư, Quản trị viên)
- ✓ Lịch sử mượn sách của từng người dùng
- ✓ Quản lý vai trò và quyền hạn
- ✓ Kích hoạt/vô hiệu hóa tài khoản
- ✓ Thành viên tự trả sách trước hạn hoặc đúng hạn
- ✓ Thành viên gửi yêu cầu nạp ví, Thủ thư/Admin duyệt trực tiếp

### 4. **Đặt Sách & Thông Báo Cho Staff**
- ✓ Thành viên đặt sách từ khu vực Member
- ✓ Dashboard của Thủ thư/Admin hiển thị danh sách đặt sách chờ xử lý
- ✓ Dashboard hiển thị yêu cầu nạp ví chờ duyệt

### 4. **Bảng Điều Khiển (Dashboard)**
- ✓ Thống kê tổng quan (sách, người dùng, phiếu mượn)
- ✓ Sách phổ biến nhất
- ✓ Lịch sử mượn gần đây
- ✓ Danh sách danh mục

### 5. **🤖 AI Hỗ Trợ**
- ✓ **Gợi ý sách thông minh**: Dựa trên lịch sử mượn của người dùng
- ✓ **Tìm kiếm thông minh**: Tìm kiếm với gợi ý tự động
- ✓ **Phân tích xu hướng**: Sách được mượn nhiều nhất
- ✓ **Phân loại tự động**: Phân loại sách dựa trên mô tả

## 🛠️ Công Nghệ Sử Dụng

- **Backend**: Laravel 9+, PHP 8+
- **Frontend**: HTML5, CSS3, Blade Templates
- **Database**: MySQL 8+
- **AI Features**: Machine Learning (Content-based recommendations)
- **Architecture**: MVC (Model-View-Controller)

## 📋 Yêu Cầu Hệ Thống

- PHP >= 8.0
- MySQL >= 8.0
- Composer
- Node.js (optional, cho assets)

## 🚀 Hướng Dẫn Cài Đặt

### 1. **Clone hoặc tải dự án**
```bash
cd d:\QLTV
```

### 2. **Cài đặt dependencies**
```bash
composer install
```

### 3. **Cấu hình file .env**
```bash
# Copy file .env (đã có sẵn)
# Chỉnh sửa thông tin database:
DB_DATABASE=library_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. **Tạo database**
```sql
CREATE DATABASE library_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. **Chạy migrations**
```bash
php artisan migrate
```

### 6. **Seed dữ liệu mẫu (tuỳ chọn)**
```bash
php artisan db:seed
```

Điều này sẽ tạo:
- 1 tài khoản admin: `admin@library.com` / `password123`
- 1 tài khoản thủ thư: `librarian@library.com` / `password123`
- 6 danh mục sách
- 5 cuốn sách mẫu
- 5 tài khoản thành viên

### 7. **Tạo key ứng dụng**
```bash
php artisan key:generate
```

### 8. **Chạy ứng dụng**
```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 👤 Tài Khoản Demo

Sau khi chạy seeder, bạn có thể đăng nhập với:

| Vai Trò | Email | Mật Khẩu |
|---------|-------|----------|
| **Admin** | admin@library.com | password123 |
| **Thủ Thư** | librarian@library.com | password123 |
| **Thành Viên** | member1@library.com | password123 |

## 📁 Cấu Trúc Thư Mục

```
app/
├── Models/                    # Database Models
│   ├── User.php
│   ├── Book.php
│   ├── Category.php
│   ├── Borrow.php
│   ├── Recommendation.php
├── Http/
│   └── Controllers/          # Logic xử lý
│       ├── BookController.php
│       ├── BorrowController.php
│       ├── UserController.php
│       ├── AIController.php
│       └── DashboardController.php

routes/
└── web.php                   # Định tuyến ứng dụng

resources/
└── views/                    # Blade templates
    ├── layout.blade.php
    ├── dashboard.blade.php
    ├── books/
    ├── borrows/
    └── users/

database/
├── migrations/               # Schema database
└── seeders/                 # Dữ liệu mẫu
```

## 🤖 Tính Năng AI Chi Tiết

### 1. **Gợi ý Sách (Recommendations)**
- Phân tích danh mục sách người dùng đã mượn
- Tìm sách cùng danh mục mà chưa mượn
- Sắp xếp theo rating cao nhất
- Endpoint: `GET /api/ai/recommendations/{userId}`

### 2. **Tìm Kiếm Thông Minh**
- Tìm kiếm theo tiêu đề, tác giả, mô tả
- Nếu kết quả ít, tự động gợi ý sách liên quan
- Endpoint: `POST /api/ai/search`

### 3. **Phân Tích Xu Hướng**
- Xác định sách được mượn nhiều nhất
- Giúp quyết định mua thêm sách
- Endpoint: `GET /api/ai/trends`

### 4. **Phân Loại Tự Động**
- Phân loại sách dựa trên mô tả nội dung
- Hỗ trợ: Tiểu thuyết, Khoa học, Lịch sử, Tự truyện
- Endpoint: `POST /api/ai/categorize`

## 🔌 API Endpoints

### Books
- `GET /books` - Danh sách sách
- `GET /books/{id}` - Chi tiết sách
- `POST /books` - Thêm sách mới
- `PUT /books/{id}` - Cập nhật sách
- `DELETE /books/{id}` - Xóa sách
- `GET /books/search?q=...` - Tìm kiếm sách

### Borrows
- `GET /borrows` - Danh sách phiếu mượn
- `POST /borrows` - Tạo phiếu mượn
- `POST /borrows/{id}/return` - Ghi nhận trả sách
- `DELETE /borrows/{id}` - Xóa phiếu mượn

### Users
- `GET /users` - Danh sách người dùng
- `GET /users/{id}` - Chi tiết người dùng
- `POST /users` - Tạo người dùng
- `PUT /users/{id}` - Cập nhật người dùng
- `DELETE /users/{id}` - Xóa người dùng

### AI Features
- `GET /api/ai/recommendations/{userId}` - Gợi ý sách
- `POST /api/ai/search` - Tìm kiếm thông minh
- `GET /api/ai/trends` - Phân tích xu hướng
- `POST /api/ai/categorize` - Phân loại sách

## 🎨 Giao Diện

Ứng dụng sử dụng:
- **CSS Grid** và **Flexbox** cho responsive design
- **Gradient backgrounds** cho thiết kế hiện đại
- **Smooth animations** cho trải nghiệm tốt
- **Adaptive layout** cho desktop và mobile

## 🔒 Bảo Mật

- Mật khẩu được hash với bcrypt
- CSRF protection cho tất cả form
- SQL injection prevention
- XSS protection qua Blade escaping

## 📊 Database Schema

### Users
- id, name, email, password, phone, address, role, is_active

### Categories
- id, name, description

### Books
- id, title, author, isbn, description, category_id, total_copies, available_copies, publisher, publication_year, language, cover_image, rating

### Borrows
- id, user_id, book_id, borrowed_at, due_date, returned_at, status, fine_amount

### Recommendations
- id, user_id, book_id, reason, confidence_score

## 🐛 Troubleshooting

### Lỗi: "SQLSTATE[HY000]: General error: 1030"
```bash
# Xóa database và tạo lại
php artisan migrate:refresh --seed
```

### Lỗi: "Class not found"
```bash
composer dump-autoload
```

### Không hiển thị ảnh bìa hoặc upload ảnh/PDF thất bại
```bash
# 1) Đảm bảo đã migrate đủ cột mới (file_path, is_digital, wallet_balance...)
php artisan migrate

# 2) Kiểm tra giới hạn upload PHP (upload_max_filesize, post_max_size)
#    PDF hiện giới hạn 20MB, ảnh bìa 2MB

# 3) Nếu dùng Windows và chưa tạo storage link,
#    hệ thống vẫn có route phục vụ ảnh bìa, nhưng bạn vẫn nên tạo link để đồng bộ:
php artisan storage:link
```

### Port 8000 đã được sử dụng
```bash
php artisan serve --port=8001
```

## 📝 Ghi Chú Phát Triển

### Tính năng có thể mở rộng:
- 🔐 Xác thực bằng Google/Facebook
- 📧 Thông báo qua email
- 📱 Mobile app
- 📊 Báo cáo chi tiết (PDF)
- 🌍 Tích hợp API sách Goodreads
- 💬 Bình luận và đánh giá sách
- 🏆 Hệ thống điểm thành viên
"# Qu-nl-th-vi-ns-"  
