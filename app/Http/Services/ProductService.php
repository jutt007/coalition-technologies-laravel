<?php

namespace App\Http\Services;

use App\Http\Traits\JsonHelper;
use App\Http\Traits\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductService
{
    use JsonHelper, ResponseHelper;

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        try {
            $products = $this->readJson();
            return $this->success([
                'products' => $products
            ]);
        }catch (\Exception $exception){
            Log::error('Unable to fetch products : '.$exception->getMessage());
            return $this->error();
        }
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function save(array $data): JsonResponse
    {
        try {
            $products = $this->readJson();
            $data['id'] = count($products) ? end($products)['id'] + 1 : 1;
            $data['submitted_at'] = now();
            $products[] = $data;
            $this->saveJson($products);

            return $this->success([
                'products' => $products
            ]);
        }catch (\Exception $exception){
            Log::error('Unable to save product : '.$exception->getMessage());
            return $this->error();
        }
    }
}
