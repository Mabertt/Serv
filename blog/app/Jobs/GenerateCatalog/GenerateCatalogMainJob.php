<?php

namespace App\Jobs\GenerateCatalog;

class GenerateCatalogMainJob extends AbstractJob
{
    public function handle(): void
    {
        $this->debug('start');

        // Крок 1: Синхронне виконання кешування (прямо зараз у цьому ж процесі)
        GenerateCatalogCacheJob::dispatchSync();

        // Крок 2: Генерація масиву завдань для обробки цін частинами (чанками)
        $chainPrices = $this->getChainPrices();

        // Крок 3: Основні підзавдання структури каталогу
        $chainMain = [
            new GenerateCategoriesJob(),
            new GenerateDeliveriesJob(),
            new GeneratePointsJob(),
        ];

        // Крок 4: Фінальні завдання (архівування та сповіщення)
        $chainLast = [
            new ArchiveUploadsJob(),
            new SendPriceRequestJob(),
        ];

        // Об'єднуємо всі етапи в один послідовний ланцюг
        $chain = array_merge($chainPrices, $chainMain, $chainLast);

        // Запуск ланцюга, де першим стартує GenerateGoodsFileJob
        GenerateGoodsFileJob::withChain($chain)->dispatch();

        $this->debug('finish');
    }

    private function getChainPrices(): array
    {
        $result = [];
        $products = collect([1, 2, 3, 4, 5]);
        $fileNum = 1;

        // Емуляція поділу великої бази товарів на порції
        foreach ($products->chunk(1) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum++;
        }

        return $result;
    }
}