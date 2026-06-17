<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Repositories\BlogCategoryRepository; // <--- Підключаємо репозиторій
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\BlogCategory;
use App\Http\Resources\Api\Blog\Admin\CategoryResource;

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
        // Отримуємо всі категорії
        $categories = Category::all();

        // Повертаємо через ресурс
        return CategoryResource::collection($categories);
    }

    // 2. Створення нової категорії (Залишається працювати через модель)
    // Створення нової категорії
    public function store(BlogCategoryCreateRequest $request) 
    {
        // Передаємо чистий масив, обсервер сам згенерує slug
        $item = (new BlogCategory())->create($request->all()); 

        if ($item) {
            return ['success' => true, 'message' => 'Успішно збережено'];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    // Оновлення категорії
    public function update(BlogCategoryUpdateRequest $request, string $id) 
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        
        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $result = $item->update($request->all());

        if ($result) {
            return ['success' => true, 'message' => 'Успішно збережено'];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }
}