<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Carbon\Carbon;

class DiggingDeeperController extends Controller
{
    public function collections()
    {
        $result = [];

        // Отримуємо всі пости, включаючи м'яко видалені
        $eloquentCollection = BlogPost::withTrashed()->get();

        // <--- ПРЯМО СЮДИ ВСТАВЛЯЄМО ТЕСТОВИЙ ДАМП
        dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray());

        // Перетворюємо Eloquent-колекцію на базову колекцію масивів
        $collection = collect($eloquentCollection->toArray());

        // 1. Вибірка першого та останнього елементів
        $result['first'] = $collection->first();
        $result['last'] = $collection->last();
        
        // 2. Фільтрація за category_id = 10
        $result['where']['data'] = $collection  
            ->where('category_id', 10)  
            ->values()  
            ->keyBy('id');  

        $result['where']['count'] = $result['where']['data']->count();
        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        // 3. Пошук першого елемента, створеного після певної дати
        $result['where_first'] = $collection
            ->firstWhere('created_at', '>', '2020-02-24 03:46:16');

        // 4. Трансформація через map (оригінальна колекція НЕ змінюється)
        $result['map']['all'] = $collection->map(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);

            return $newItem;
        });  

        $result['map']['not_exists'] = $result['map']['all']->where('exists', '=', false)->values()->keyBy('item_id');  

        // 5. Трансформація через transform (оригінальна колекція ЗМІНЮЄТЬСЯ)
        $collection->transform(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);
            $newItem->created_at = Carbon::parse($item['created_at']);

            return $newItem;
        });
        
        $newItem = new \stdClass;
        $newItem->id = 9999;        
        
        $newItem2 = new \stdClass;
        $newItem2->id = 8888;

        // 6. Додавання елементів у початок та кінець колекції
        $newItemFirst = $collection->prepend($newItem)->first(); 
        $newItemLast = $collection->push($newItem2)->last(); 
        $pulledItem = $collection->pull(1); // Витягуємо елемент з ключем 1

        // 7. Складна фільтрація за датами за допомогою Carbon
        $filtered = $collection->filter(function ($item) {
            if (empty($item->created_at)) {
                return false;
            }
            $byDay = $item->created_at->isFriday();   
            $byDate = $item->created_at->day == 11;

            return $byDay && $byDate;
        });

        // 8. Сортування
        $sortedSimpleCollection = collect([5, 3, 1, 2, 4])->sort()->values();
        $sortedAscCollection = $collection->sortBy('created_at');
        $sortedDescCollection = $collection->sortByDesc('item_id');

        dd(compact('sortedSimpleCollection', 'sortedAscCollection', 'sortedDescCollection'));
        
        // Поки що повертаємо фінальний згенерований масив $result на екран
        return $result;
    }
}