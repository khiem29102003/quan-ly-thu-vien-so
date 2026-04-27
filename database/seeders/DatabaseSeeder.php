<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Book;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Tạo người dùng mặc định (idempotent)
        User::updateOrCreate(
            ['email' => 'admin@library.com'],
            [
                'name' => 'Quản Trị Viên',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'phone' => '0123456789',
                'role' => 'admin',
                'is_active' => true,
                'wallet_balance' => 0,
                'outstanding_debt' => 0,
            ]
        );

        User::updateOrCreate(
            ['email' => 'librarian@library.com'],
            [
                'name' => 'Thủ Thư',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'phone' => '0123456788',
                'role' => 'librarian',
                'is_active' => true,
                'wallet_balance' => 0,
                'outstanding_debt' => 0,
            ]
        );

        // Tạo các danh mục
        $categories = [
            ['name' => 'Tiểu Thuyết', 'description' => 'Các tác phẩm tiểu thuyết hiện đại và kinh điển'],
            ['name' => 'Tiểu Thuyết Lịch Sử', 'description' => 'Tiểu thuyết lấy bối cảnh lịch sử'],
            ['name' => 'Trinh Thám', 'description' => 'Điều tra phá án, suy luận logic'],
            ['name' => 'Kinh Dị', 'description' => 'Không khí rùng rợn, hồi hộp'],
            ['name' => 'Khoa Học Viễn Tưởng', 'description' => 'Tương lai, công nghệ, vũ trụ'],
            ['name' => 'Giả Tưởng', 'description' => 'Thế giới phép thuật và huyền ảo'],
            ['name' => 'Phiêu Lưu', 'description' => 'Hành trình khám phá và thử thách'],
            ['name' => 'Lãng Mạn', 'description' => 'Tình yêu, cảm xúc, gia đình'],
            ['name' => 'Văn Học Việt Nam', 'description' => 'Tác phẩm văn học trong nước'],
            ['name' => 'Văn Học Nước Ngoài', 'description' => 'Tác phẩm văn học thế giới'],
            ['name' => 'Khoa Học', 'description' => 'Kiến thức khoa học tự nhiên và ứng dụng'],
            ['name' => 'Công Nghệ Thông Tin', 'description' => 'Lập trình, dữ liệu, an toàn thông tin'],
            ['name' => 'Toán Học', 'description' => 'Toán cơ bản đến nâng cao'],
            ['name' => 'Vật Lý', 'description' => 'Cơ học, điện, quang, hiện đại'],
            ['name' => 'Hóa Học', 'description' => 'Hóa vô cơ, hữu cơ và ứng dụng'],
            ['name' => 'Sinh Học', 'description' => 'Sinh học, y sinh, di truyền'],
            ['name' => 'Y Học', 'description' => 'Sức khỏe, bệnh học, chăm sóc'],
            ['name' => 'Tâm Lý', 'description' => 'Tâm lý học, hành vi, cảm xúc'],
            ['name' => 'Giáo Dục', 'description' => 'Phương pháp giảng dạy và học tập'],
            ['name' => 'Ngoại Ngữ', 'description' => 'Tài liệu học ngôn ngữ'],
            ['name' => 'Từ Điển', 'description' => 'Từ điển, bách khoa, tra cứu'],
            ['name' => 'Lịch Sử', 'description' => 'Lịch sử Việt Nam và thế giới'],
            ['name' => 'Địa Lý', 'description' => 'Địa lý tự nhiên và kinh tế'],
            ['name' => 'Chính Trị', 'description' => 'Chính trị học và quản trị'],
            ['name' => 'Kinh Tế', 'description' => 'Kinh tế vĩ mô và vi mô'],
            ['name' => 'Kinh Doanh', 'description' => 'Quản trị, vận hành, chiến lược'],
            ['name' => 'Marketing', 'description' => 'Thương hiệu, truyền thông, bán hàng'],
            ['name' => 'Khởi Nghiệp', 'description' => 'Ý tưởng, mô hình, gọi vốn'],
            ['name' => 'Tài Chính', 'description' => 'Tài chính cá nhân và doanh nghiệp'],
            ['name' => 'Đầu Tư', 'description' => 'Chứng khoán, bất động sản, quỹ'],
            ['name' => 'Quản Trị Nhân Sự', 'description' => 'Tuyển dụng, đào tạo, văn hóa'],
            ['name' => 'Kỹ Năng Mềm', 'description' => 'Giao tiếp, lãnh đạo, teamwork'],
            ['name' => 'Phát Triển Bản Thân', 'description' => 'Thói quen, mục tiêu, động lực'],
            ['name' => 'Triết Học', 'description' => 'Tư duy, lập luận, hệ giá trị'],
            ['name' => 'Tôn Giáo', 'description' => 'Tôn giáo và tín ngưỡng'],
            ['name' => 'Văn Hóa', 'description' => 'Văn hóa, phong tục, nghệ thuật'],
            ['name' => 'Nghệ Thuật', 'description' => 'Hội họa, điêu khắc, thiết kế'],
            ['name' => 'Âm Nhạc', 'description' => 'Lý thuyết và lịch sử âm nhạc'],
            ['name' => 'Điện Ảnh', 'description' => 'Lịch sử và phân tích phim'],
            ['name' => 'Nhiếp Ảnh', 'description' => 'Kỹ thuật chụp và bố cục'],
            ['name' => 'Ẩm Thực', 'description' => 'Nấu ăn, dinh dưỡng, công thức'],
            ['name' => 'Du Lịch', 'description' => 'Cẩm nang, trải nghiệm, điểm đến'],
            ['name' => 'Thể Thao', 'description' => 'Kỹ thuật, chiến thuật, luyện tập'],
            ['name' => 'Sức Khỏe', 'description' => 'Chăm sóc sức khỏe, fitness'],
            ['name' => 'Thiếu Nhi', 'description' => 'Sách thiếu nhi, truyện tranh'],
            ['name' => 'Truyện Tranh', 'description' => 'Manga, comic, graphic novel'],
            ['name' => 'Tự Truyện', 'description' => 'Hồi ký, câu chuyện đời thực'],
            ['name' => 'Du Ký', 'description' => 'Nhật ký hành trình, trải nghiệm'],
            ['name' => 'Kỹ Thuật', 'description' => 'Cơ khí, điện, tự động hóa'],
            ['name' => 'Nông Nghiệp', 'description' => 'Trồng trọt, chăn nuôi, sinh thái'],
            ['name' => 'Môi Trường', 'description' => 'Bảo tồn, phát triển bền vững'],
            ['name' => 'Luật', 'description' => 'Pháp luật và quy định'],
            ['name' => 'Quan Hệ Quốc Tế', 'description' => 'Ngoại giao và địa chính trị'],
            ['name' => 'Khoa Học Dữ Liệu', 'description' => 'Phân tích, thống kê, AI'],
            ['name' => 'Trí Tuệ Nhân Tạo', 'description' => 'Học máy, học sâu, ứng dụng'],
            ['name' => 'An Toàn Thông Tin', 'description' => 'Bảo mật, mạng, an ninh'],
            ['name' => 'Thiết Kế', 'description' => 'UI/UX, đồ họa, sáng tạo'],
            ['name' => 'Quản Lý Dự Án', 'description' => 'Kế hoạch, tiến độ, kiểm soát'],
            ['name' => 'Hướng Nghiệp', 'description' => 'Định hướng nghề, kỹ năng nghề'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['name' => $cat['name']], $cat);
        }

        // Tạo các sách mẫu
        $books = [
            [
                'title' => 'Những Đứa Con Của Thời Gian',
                'author' => 'Stephen King',
                'isbn' => '978-0451191730',
                'description' => 'Một tác phẩm kinh điển về tình bạn và cuộc sống.',
                'category_id' => 1,
                'total_copies' => 5,
                'available_copies' => 5,
                'publisher' => 'Viking',
                'publication_year' => 2016,
                'language' => 'Tiếng Việt',
                'rating' => 4,
                'source_type' => 'license',
                'source_name' => 'Nha cung cap ebook A',
                'borrow_fee' => 3000,
                'daily_late_fee' => 5000,
                'is_digital' => false,
            ],
            [
                'title' => 'Tạm Biệt Kỷ Nguyên Thông Tin',
                'author' => 'Steven Pinker',
                'isbn' => '978-0399564628',
                'description' => 'Khám phá lịch sử và tương lai của tư duy con người.',
                'category_id' => 2,
                'total_copies' => 3,
                'available_copies' => 3,
                'publisher' => 'Penguin',
                'publication_year' => 2018,
                'language' => 'Tiếng Việt',
                'rating' => 5,
                'source_type' => 'purchase',
                'source_name' => 'Nha sach B',
                'borrow_fee' => 2000,
                'daily_late_fee' => 5000,
                'is_digital' => false,
            ],
            [
                'title' => 'Lịch Sử Việt Nam Qua Các Thời Kỳ',
                'author' => 'Bùi Xuân Phái',
                'isbn' => '978-1234567890',
                'description' => 'Tìm hiểu chi tiết về các giai đoạn phát triển của nước ta.',
                'category_id' => 3,
                'total_copies' => 4,
                'available_copies' => 4,
                'publisher' => 'Nxb Tổng Hợp',
                'publication_year' => 2015,
                'language' => 'Tiếng Việt',
                'rating' => 4,
                'source_type' => 'donation',
                'source_name' => 'Tac gia quyen tang',
                'borrow_fee' => 0,
                'daily_late_fee' => 3000,
                'is_digital' => false,
            ],
            [
                'title' => 'Cuộc Sống Của Một Người Thay Đổi',
                'author' => 'Malcolm Gladwell',
                'isbn' => '978-0316017923',
                'description' => 'Những bài học từ cuộc sống thực tế.',
                'category_id' => 4,
                'total_copies' => 6,
                'available_copies' => 6,
                'publisher' => 'Little Brown',
                'publication_year' => 2008,
                'language' => 'Tiếng Việt',
                'rating' => 5,
                'source_type' => 'purchase',
                'source_name' => 'Nha sach C',
                'borrow_fee' => 2000,
                'daily_late_fee' => 5000,
                'is_digital' => false,
            ],
            [
                'title' => 'Thế Giới Thần Kỳ',
                'author' => 'J.K. Rowling',
                'isbn' => '978-0439708180',
                'description' => 'Cuộc phiêu lưu của một cậu bé phù thủy.',
                'category_id' => 5,
                'total_copies' => 10,
                'available_copies' => 10,
                'publisher' => 'Bloomsbury',
                'publication_year' => 2003,
                'language' => 'Tiếng Việt',
                'rating' => 5,
                'source_type' => 'license',
                'source_name' => 'Nha cung cap ebook D',
                'borrow_fee' => 4000,
                'daily_late_fee' => 5000,
                'is_digital' => false,
            ],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(['isbn' => $book['isbn']], $book);
        }

        // Tạo một số người dùng mẫu
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => 'member' . $i . '@library.com'],
                [
                    'name' => 'Thành Viên ' . $i,
                    'password' => password_hash('password123', PASSWORD_BCRYPT),
                    'phone' => '010000000' . $i,
                    'address' => 'Địa chỉ ' . $i,
                    'role' => 'member',
                    'is_active' => true,
                    'wallet_balance' => 100000,
                    'outstanding_debt' => 0,
                ]
            );
        }

        echo "✓ Dữ liệu mẫu đã được tạo thành công!\n";
    }
}
