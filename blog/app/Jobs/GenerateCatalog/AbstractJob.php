<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Конструктор автоматично призначає виділену чергу.
     */
    public function __construct()
    {
        $this->onQueue('generate-catalog');
    }

    /**
     * Дефолтний обробник завдання.
     */
    public function handle(): void
    {
        $this->debug('done');
    }

    /**
     * Кастомний метод логування для відстеження черговості у файлі laravel.log.
     */
    protected function debug(string $msg): void
    {
        $class = static::class;
        Log::info("{$msg} [{$class}]");
    }
}