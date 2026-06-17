<?php

namespace App\Jobs\GenerateCatalog;

class GeneratePricesFileChunkJob extends AbstractJob
{
    public function __construct(protected $chunk, protected $fileNum)
    {
        parent::__construct();
    }
}