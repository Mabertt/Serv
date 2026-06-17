<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostCreateRequest extends FormRequest
{
    /**
     * Визначаємо, чи дозволено користувачу робити цей запит.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валідації для створення статті.
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|min:5|max:200|unique:blog_posts',
            'slug'        => 'max:200|unique:blog_posts',
            'content_raw' => 'required|string|min:5|max:10000',
            'category_id' => 'required|integer|exists:blog_categories,id',
        ];
    }
    
    /**
     * Кастомні повідомлення про помилки.
     */
    public function messages(): array
    {
        return [
            'title.required'  => 'Введіть заголовок статті',
            'slug.max'        => 'Максимальна довжина [:max]',
            'content_raw.min' => 'Мінімальна довжина статті [:min] символів',
        ];
    }
    
    /**
     * Кастомні назви атрибутів.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Заголовок статті',
        ];
    }
}