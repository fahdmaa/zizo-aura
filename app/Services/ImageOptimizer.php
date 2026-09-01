<?php

namespace App\Services;

class ImageOptimizer
{
    /**
     * Compress and resize base64 data URL to optimized lightweight JPEG.
     */
    public static function optimizeBase64(?string $dataUrl, int $maxWidth = 800, int $quality = 80): ?string
    {
        if (empty($dataUrl) || ! str_starts_with($dataUrl, 'data:image/')) {
            return $dataUrl;
        }

        // If already under 40 KB, keep as is
        if (strlen($dataUrl) < 40000) {
            return $dataUrl;
        }

        try {
            $parts = explode(',', $dataUrl, 2);
            if (count($parts) !== 2) {
                return $dataUrl;
            }

            $raw = base64_decode($parts[1]);
            if ($raw === false) {
                return $dataUrl;
            }

            $src = @imagecreatefromstring($raw);
            if (! $src) {
                return $dataUrl;
            }

            $width = imagesx($src);
            $height = imagesy($src);

            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int) round(($height * $maxWidth) / $width);
            } else {
                $newWidth = $width;
                $newHeight = $height;
            }

            $dst = imagecreatetruecolor($newWidth, $newHeight);
            $white = imagecolorallocate($dst, 255, 255, 255);
            imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $white);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            ob_start();
            imagejpeg($dst, null, $quality);
            $jpegData = ob_get_clean();

            if ($jpegData && strlen($jpegData) < strlen($raw)) {
                return 'data:image/jpeg;base64,' . base64_encode($jpegData);
            }
        } catch (\Throwable $e) {
            // Silently fallback to original
        }

        return $dataUrl;
    }

    /**
     * Optimize array of gallery images.
     */
    public static function optimizeGallery(?array $gallery): ?array
    {
        if (empty($gallery) || ! is_array($gallery)) {
            return $gallery;
        }

        return array_map(fn ($img) => is_string($img) ? self::optimizeBase64($img, 800, 80) : $img, $gallery);
    }
}
