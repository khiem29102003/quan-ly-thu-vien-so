<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReservation extends Model
{
    protected $fillable = ['user_id', 'book_id', 'status', 'reserved_at', 'pickup_by'];

    protected $dates = ['reserved_at', 'pickup_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function isExpired()
    {
        return $this->status === 'pending' && $this->pickup_by && $this->pickup_by < now();
    }
}
