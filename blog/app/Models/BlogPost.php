<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- 1. ПЕРЕВІР ЦЕЙ IMPORT

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes; // <--- 2. ПЕРЕВІР ЦЕЙ РЯДОК ВСЕРЕДИНІ КЛАСУ

    // Твої інші налаштування моделі (наприклад, $fillable), якщо вони там є
}