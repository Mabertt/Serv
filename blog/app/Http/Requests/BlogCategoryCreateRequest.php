<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryCreateRequest extends FormRequest
{
    /**
     * Визначаємо, чи дозволено користувачу робити цей запит.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валідації для створення категорії.
     */
    public function rules(): array
    {
        return [
            // Назва обов'язкова, мінімум 3 символи (як у Nuxt), має бути унікальною в таблиці blog_categories
            'title'       => 'required|min:3|max:200|unique:blog_categories,title',
            // Slug не обов'язковий (бо генерується автоматично), але якщо є — має бути унікальним
            'slug'        => 'nullable|max:200|unique:blog_categories,slug',
            // Опис не обов'язковий
            'description' => 'nullable|string|max:1000',
        ];
    }
    
    /**
     * Кастомні повідомлення про помилки.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Введіть назву категорії',
            'title.min'      => 'Назва має містити мінімум [:min] символи',
            'title.unique'   => 'Категорія з такою назвою вже існує',
            'slug.max'       => 'Максимальна довжина slug [:max] символів',
            'slug.unique'    => 'Такий slug вже зайнятий',
        ];
    }
    
    /**
     * Кастомні назви атрибутів.
     */
    public function attributes(): array
    {
        return [
            'title'       => 'Назва категорії',
            'description' => 'Опис категорії',
        ];
    }
}