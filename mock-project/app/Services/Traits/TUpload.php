<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

trait TUpload
{
    public string|null $disk;

    public function __construct()
    {
        $this->disk = env('FILESYSTEM_DISK', 'public');
    }

    /**
     * @param $file
     * @param string $path
     * @param $disk
     * @return string|bool
     */
    public function uploadFile($file, string $path = '', $disk = null): string|bool
    {
        $disk = $this->getDisk($disk);
        return Storage::disk($disk)->put($path, $file);
    }

    /**
     * @param $path
     * @param $disk
     * @return void
     */
    public function deleteFile($path, $disk = null): void
    {
        $disk = $this->getDisk($disk);

        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }

    /**
     * @param $files
     * @param $disk
     * @return void
     */
    public function deleteMultipleFiles($files, $disk = null): void
    {
        $disk = $this->getDisk($disk);

        foreach ($files as $file) {
            $this->deleteFile($file, $disk);
        }
    }

    /**
     * @param $files
     * @param $path
     * @param $disk
     * @return array
     */
    public function uploadMultipleFiles($files, $path, $disk = null): array
    {
        $disk = $this->getDisk($disk);

        return array_map(function ($file) use ($path, $disk) {
            return $this->uploadFile($file, $path, $disk);
        }, $files);
    }

    /**
     * @param $originalName
     * @return array|string|string[]
     */
    public function generateUniqueFileName($originalName): array|string
    {
        return str_replace('/', '', Hash::make($originalName . time()));
    }

    /**
     * @param $disk
     * @return mixed
     */
    private function getDisk($disk = null): mixed
    {
        return $disk ?? $this->disk;
    }
}
