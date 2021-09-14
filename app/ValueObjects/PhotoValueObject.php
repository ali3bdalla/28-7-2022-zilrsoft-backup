<?php

namespace App\ValueObjects;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoValueObject
{
    private string $storagePath;
    private string $storageDriver;

    /**
     * @param string $storagePath
     * @param string $storageDriver
     */
    public function __construct(string $storagePath = "", string $storageDriver = 'public')
    {
        $this->storagePath = $storagePath;
        $this->storageDriver = $storageDriver;
    }

    public static function upload(UploadedFile $file, $path, $disk = 'public'): PhotoValueObject
    {
        $storagePath = $file->store($path, ['disk' => $disk]);
        return new static($storagePath, $disk);
    }

    /**
     * @return string
     */
    public function getStoragePath(): string
    {
        return $this->storagePath;
    }

    public function getPhotoUrl(): string
    {
        return $this->getDisk()->url($this->storagePath);
    }

    private function getDisk(): Filesystem
    {
        return Storage::disk($this->storageDriver);
    }

    /**
     * @throws FileNotFoundException
     */
    public function getPhoto(): string
    {
        return $this->getDisk()->get($this->storagePath);
    }

    /**
     * @return string
     */
    public function getStorageDriver(): string
    {
        return $this->storageDriver;
    }

}
