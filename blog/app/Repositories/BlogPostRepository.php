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
    /**
     * Отримати список статей з категоріями та авторами
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
            ->select($columns)
            ->orderBy('id', 'DESC')
            // Оптимізоване завантаження зв'язаних даних
            ->with([
                'category' => function ($query) {
                    $query->select(['id', 'title']);
                },
                'user:id,name', // Короткий синтаксис Laravel для вибірки певних полів
            ])
            ->paginate(25);
            
        return $result;
    }
}