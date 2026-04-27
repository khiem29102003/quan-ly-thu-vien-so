<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'isbn', 'description', 'category_id',
        'total_copies', 'available_copies', 'publisher', 'publication_year',
        'language', 'cover_image', 'rating',
        'source_type', 'source_name', 'source_url',
        'borrow_fee', 'daily_late_fee', 'is_digital', 'file_path'
    ];

    protected $casts = [
        'is_digital' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function isAvailable()
    {
        return $this->available_copies > 0;
    }
}
