<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Repositories\BlogPostRepository;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    /**
     * Впроваджуємо репозиторій статей через конструктор
     */
    public function __construct(private BlogPostRepository $blogPostRepository)
    {
        parent::__construct();
    }

    /**
     * Відобразити список статей з пагінацією
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return $paginator;
    }

    /**
     * Зберегти нову статтю в базі (реалізуємо в наступних лабах)
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Оновити існуючу статтю (реалізуємо в наступних лабах)
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Видалити статтю
     */
    public function destroy(string $id)
    {
        //
    }
}