<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Repositories\BlogCategoryRepository; // <--- Підключаємо репозиторій
use Illuminate\Support\Str;
use App\Models\BlogCategory;

class CategoryController extends BaseController
{
    // Використовуємо сучасне впровадження залежностей через конструктор (Dependency Injection)
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        parent::__construct();
    }

    // 1. Отримання списку через репозиторій з оптимізованими полями
    public function index()
    {
        // Замість прямого запиту викликаємо метод репозиторія
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);
        return $paginator; 
    }

    // 2. Створення нової категорії (Залишається працювати через модель)
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $item = (new BlogCategory())->create($data); 

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    // 3. Оновлення існуючої категорії через репозиторій
    public function update(BlogCategoryUpdateRequest $request, string $id)
    {
        // Використовуємо репозиторій для пошуку запису на редагування
        $item = $this->blogCategoryRepository->getEdit($id);
        
        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $result = $item->update($data);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }
}