<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Resources\Api\Blog\Admin\PostResource;

class PostController extends BaseController
{
    use DispatchesJobs; // Додаємо трейт для можливості виклику $this->dispatch()

    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private BlogCategoryRepository $blogCategoryRepository
    ) {
        parent::__construct();
    }

    public function index()
    {
        // Отримуємо пагіновані дані (припустимо, 25 на сторінку)
        $paginator = Post::paginate(25);

        // Обгортаємо пагінацію в API Ресурс
        // collection автоматично додасть meta та links
        return PostResource::collection($paginator);
    }

    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();
        $item = (new BlogPost())->create($data);

        if ($item) {
            // Створюємо завдання та відправляємо його в чергу через трейт
            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job);

            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }
    public function show($id)
{
    // Додай цей рядок для тесту, щоб зрозуміти, чи взагалі метод викликається
    \Log::info('Запит до API для поста ID: ' . $id);
    
    return \App\Models\Post::with(['user', 'category'])->findOrFail($id);
}
    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $result = $item->update($request->all());

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    public function destroy(string $id)
    {
        $result = BlogPost::destroy($id);

        if ($result) {
            // Відправляємо завдання в чергу статичним методом dispatch() 
            // та додаємо штучну затримку виконання на 20 секунд
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);

            return [
                'success' => true,
                'message' => "Запис id=[{$id}] успішно видалено (SoftDelete)"
            ];
        } else {
            return ['message' => 'Помилка видалення запису'];
        }
    }
}