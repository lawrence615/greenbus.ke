<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourImageService
{
    protected string $disk = 'do';
    protected string $basePath = 'greenbus/images';

    public function store(UploadedFile $image, string $tourCode, string $tourTitle): string
    {
        $filename = Str::slug($tourTitle) . '-' . Str::random(8) . '.' . $image->getClientOriginalExtension();
        $path = "{$this->basePath}/{$tourCode}/{$filename}";

        Storage::disk($this->disk)->put($path, file_get_contents($image), 'public');

        return $path;
    }

    public function delete(string $path): void
    {
        Storage::disk($this->disk)->delete($path);
    }

    public function deleteTourDirectory(string $tourCode): void
    {
        Storage::disk($this->disk)->deleteDirectory("{$this->basePath}/{$tourCode}");
    }
}
