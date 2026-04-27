<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;

class BookPolicy
{
    /**
     * Xem danh sách sách - tất cả user đều được xem
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Xem chi tiết một sách - tất cả user đều được xem
     */
    public function view(User $user, Book $book)
    {
        return true;
    }

    /**
     * Tạo sách mới - chỉ admin và librarian
     */
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'librarian']);
    }

    /**
     * Cập nhật sách - chỉ admin và librarian
     */
    public function update(User $user, Book $book)
    {
        return in_array($user->role, ['admin', 'librarian']);
    }

    /**
     * Xóa sách - chỉ admin
     */
    public function delete(User $user, Book $book)
    {
        return $user->role === 'admin';
    }
}
