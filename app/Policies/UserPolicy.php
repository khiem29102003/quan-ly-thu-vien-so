<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Xem danh sách users - chỉ admin và librarian
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'librarian']);
    }

    /**
     * Xem chi tiết user - admin, librarian và chính mình
     */
    public function view(User $user, User $model)
    {
        return in_array($user->role, ['admin', 'librarian']) || $user->id === $model->id;
    }

    /**
     * Tạo user mới - chỉ admin và librarian
     */
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'librarian']);
    }

    /**
     * Cập nhật user - admin, librarian và chính mình
     */
    public function update(User $user, User $model)
    {
        return in_array($user->role, ['admin', 'librarian']) || $user->id === $model->id;
    }

    /**
     * Xóa user - chỉ admin
     */
    public function delete(User $user, User $model)
    {
        return $user->role === 'admin' && $user->id !== $model->id; // Không cho xóa chính mình
    }

    /**
     * Thay đổi role - chỉ admin
     */
    public function changeRole(User $user)
    {
        return $user->role === 'admin';
    }
}
