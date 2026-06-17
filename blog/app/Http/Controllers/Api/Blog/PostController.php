<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Requests\BlogPostUpdateRequest;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostController extends BaseController
{
    // Оголошуємо та впроваджуємо обидва репозиторії через сучасний конструктор PHP
    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private BlogCategoryRepository $blogCategoryRepository
    ) {
        parent::__construct();
    }

    /**
     * Відобразити список статей з пагінацією та зв'язками
     */
    public function index()
    {
        return $this->blogPostRepository->getAllWithPaginate();
    }

    /**
     * Оновити існуючу статтю в базі даних
     */
    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Якщо статтю публікують вперше — ставимо поточний час через Carbon
        if (empty($item->published_at) && !empty($data['is_published'])) {
            $data['published_at'] = Carbon::now();
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