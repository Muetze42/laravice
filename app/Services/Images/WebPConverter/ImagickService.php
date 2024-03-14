<?php

namespace App\Services\Images\WebPConverter;

use Imagick;

class ImagickService
{
    /**
     * Convert images to WebP format.
     *
     * @throws \ImagickException
     */
    public static function toWebp(string $file, int $quality): string
    {
        $target = $file . '-ImagickToWebP-' . $quality . '.webp';

        if (!file_exists($target)) {
            $image = new Imagick($file);
            $image->setImageFormat('WEBP');
            $image->setImageCompressionQuality($quality);
            $type = exif_imagetype($file);
            if ($type == IMAGETYPE_PNG) {
                $image->setOption('webp:lossless', 'true');
            }

            $image->writeImage($target);
        }

        return $target;
    }
}
