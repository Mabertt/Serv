<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasFactory, SoftDeletes;

    const ROOT = 1;

    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'description',
    ];

    // Автоматично додавати віртуальне поле у JSON-структуру
    protected $appends = ['parent_title'];

    /**
     * Зв'язок: категорія належить до батьківської категорії
     */
    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    /**
     * Аксесуар (Accessor) для отримання назви батьківської категорії
     */
    public function getParentTitleAttribute(): string
    {
        return $this->parentCategory->title 
            ?? ($this->isRoot() ? 'Корінь' : '???');
    }

    /**
     * Перевірка, чи є категорія головним коренем
     */
    public function isRoot(): bool
    {
        return $this->id === self::ROOT;
    }
}