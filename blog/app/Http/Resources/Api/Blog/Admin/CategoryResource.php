<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Трансформація ресурсу в масив.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'parent_id'    => $this->parent_id,
            'title' => $this->title,
            'slug'  => $this->slug,
            'description' => $this->description,
            // Додай сюди інші поля, якщо вони є в таблиці categories
        ];
    }
}