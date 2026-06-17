<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogCategoryRepository.
 */
class BlogCategoryRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Отримати модель для редагування в адмінці
     * @param int|string $id
     * @return Model|null
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
    
    /**
     * Отримати список категорій для виводу в випадаючий список з оптимізацією
     */
    public function getForComboBox()
    {
        $columns = implode(', ', [
            'id',
            'CONCAT (id, ". ", title) AS id_title',
        ]);

        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            ->toBase() // Оптимізація: повертає stdClass замість важкої моделі Eloquent
            ->get();

        return $result;
    }

    /**
     * Отримати категорії для виводу пагінатором із обмеженням полів
     */
    public function getAllWithPaginate($perPage = null)
    {
        $columns = ['id', 'title', 'parent_id'];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->with([
                'parentCategory:id,title', // Завантажуємо лише потрібні колонки батька
            ])
            ->paginate($perPage);
            
        return $result;
    }
}