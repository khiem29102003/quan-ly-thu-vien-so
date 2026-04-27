<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_date',
        'returned_at',
        'status',
        'fine_amount',
        'borrow_fee',
        'late_fee',
        'late_fee_collected',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function isOverdue()
    {
        return $this->status === 'borrowed' && now() > $this->due_date;
    }

    public function calculateFine()
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        $days = now()->diffInDays($this->due_date);
        return $days * 5000; // 5000 VND per day
    }
}
