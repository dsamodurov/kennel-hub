<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class StoresImagesAsWebp
{
    public static function store(UploadedFile $file, string $directory, int $quality = 82): array
    {
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = Str::slug($baseName) ?: 'image';
        $datePath = now()->format('Y/m');
        $fileName = sprintf('%s-%s.webp', $slug, Str::random(6));
        $path = trim($directory, '/') . '/' . $datePath . '/' . $fileName;

        $contents = file_get_contents($file->getRealPath());
        if ($contents === false) {
            throw new RuntimeException('Failed to read image');
        }

        $image = @imagecreatefromstring($contents);
        if (! $image) {
            throw new RuntimeException('Unsupported image format');
        }

        $width = imagesx($image) ?: null;
        $height = imagesy($image) ?: null;

        ob_start();
        $saved = imagewebp($image, null, $quality);
        $webpData = ob_get_clean();
        imagedestroy($image);

        if (! $saved || $webpData === false) {
            throw new RuntimeException('Failed to convert image');
        }

        Storage::disk('public')->put($path, $webpData);

        return [
            'path' => $path,
            'size' => strlen($webpData),
            'width' => $width,
            'height' => $height,
            'mime_type' => 'image/webp',
        ];
    }
}
