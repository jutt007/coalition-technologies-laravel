<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait JsonHelper
{
    private $file = 'products.json';

    /**
     * @return array|mixed
     */
    private function readJson()
    {
        if (Storage::exists($this->file)) {
            return json_decode(Storage::get($this->file), true);
        }
        return [];
    }

    /**
     * @param $data
     * @return void
     */
    private function saveJson($data)
    {
        Storage::put($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }
}
