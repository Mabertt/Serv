<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model
{
    use HasFactory;
    const ROOT = 1;

    // Автоматично додавати аксесуар у JSON відповіді
    protected $appends = ['parent_title'];

    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    public function getParentTitleAttribute()
    {
        return $this->parentCategory->title 
            ?? ($this->isRoot() ? 'Корінь' : '???');
    }

    public function isRoot()
    {
        return $this->id === self::ROOT;
    }
}
