<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;

/**
 * Class BlogPostRepository.
 * Репозиторій для роботи зі статтями блогу.
 */
class BlogPostRepository extends CoreRepository
{
    /**
     * Визначаємо клас моделі, з якою працює репозиторій
     */
    protected function getModelClass()
    {
        return Model::class; 
    }
 
    /**
     * Отримати список статей для виводу пагінатором (Оптимізований)
     * * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate()
    {
        $columns = [
            'id', 
            'title', 
            'slug', 
            'is_published', 
            'published_at', 
            'user_id', 
            'category_id'
        ];

        $result = $this->startConditions()
            ->select($columns) // Оптимізація: не тягнемо контент статті в список
            ->orderBy('id', 'DESC') // Спочатку нові статті
            ->paginate(25);
            
        return $result;
    }

    /**
     * Отримати модель статті для редагування в адмінці
     * * @param int|string $id
     * @return Model|null
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
}