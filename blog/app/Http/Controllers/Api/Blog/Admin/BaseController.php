<?php

namespace App\Http\Controllers\Api\Blog\Admin;

// Чітко вказуємо шлях до нашого минулого базового контролера
use App\Http\Controllers\Api\Blog\BaseController as BlogBaseController;

abstract class BaseController extends BlogBaseController
{
    // Тут порожньо, як і просить методичка
    public function __construct()
    {
        // Ініціалізація загальних елементів адмінки, якщо знадобиться в майбутньому
    }
}
