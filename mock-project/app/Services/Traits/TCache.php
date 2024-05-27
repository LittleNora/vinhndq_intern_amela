<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait TCache
{
    private $cacheKey = null;

    public function setCacheKey($key): void
    {
        $this->cacheKey = $key;
    }

    public function getCacheKey()
    {
        return $this->cacheKey ?? self::CACHE_KEY;
    }

    public function getFromCache($id = null, $cachedKeyCheckExist = 'id')
    {
        $dataFromCache = Cache::get($this->getCacheKey(), collect());

        return $id && $dataFromCache->isNotEmpty() ? ($dataFromCache->first(fn($item) => $item[$cachedKeyCheckExist] === $id) ?? collect()) : $dataFromCache;
    }

    public function cacheOneItem($data, $cachedKeyCheckExist = 'id', $keyCheckExist = null)
    {
        Log::info("=======> " . strtoupper($this->getCacheKey()) . " <=======");

        $dataFromCache = $this->getFromCache();

        $keyCheckExist = $keyCheckExist ?? $cachedKeyCheckExist;

        Log::info('gaugau' . json_encode($data));

        if (($dataFromCache->isNotEmpty() && $dataFromCache->contains($cachedKeyCheckExist, $data[$keyCheckExist]))) {
            Log::info('Data already exists in cache');
            $dataFromCache = $dataFromCache->map(fn($item) => $item[$cachedKeyCheckExist] == $data[$keyCheckExist] ? $data : $item);
        } else {
            Log::info('Data does not exist in cache');
            Log::info('Data to cache: ' . json_encode($data));
            $dataFromCache->push($data);
        }

        Log::info('Data from cache: ' . json_encode($dataFromCache));

        $this->cache($dataFromCache, $cachedKeyCheckExist);

        Log::info('Data cached successfully');
        Log::info("================================================");
        Log::info("================================================");

        return $data;
    }

    public function cache($data, $uniqueKey = 'id', $expires = null)
    {
        $expires = $expires ?? now()->endOf('day');

        $dataFromCache = $this->getFromCache();

        $data = $data->merge($dataFromCache)->unique($uniqueKey);

        Cache::put($this->getCacheKey(), $data, $expires);
    }

    public function removeOneItem($data, $cachedKeyCheck = 'id', $keyCheck = null)
    {
        $keyCheck = $keyCheck ?? $cachedKeyCheck;

        $dataFromCache = $this->getFromCache();

        $this->cache($dataFromCache->filter(fn($item) => $item[$cachedKeyCheck] !== $data[$keyCheck]));

        return $data;
    }

    public function flushCache(): void
    {
        Cache::forget($this->getCacheKey());
    }

    public function getFromCacheByDate($date = null, $field = 'date')
    {
        $date = $date ?? now()->format('Y-m-d');

        return $this->getFromCache()->where($field, $date)->get();
    }
}
