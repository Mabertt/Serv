<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessVideoJob;
use App\Jobs\GenerateCatalog\GenerateCatalogMainJob;
use Illuminate\Http\JsonResponse;

class DiggingDeeperController extends Controller
{
    public function processVideo(): JsonResponse
    {
        ProcessVideoJob::dispatch();
        
        return response()->json(['status' => 'Video job dispatched']);
    }
    public function prepareCatalog(): JsonResponse
    {
        GenerateCatalogMainJob::dispatch();

        return response()->json(['status' => 'Catalog generation chain dispatched']);
    }
}