<?php

namespace App\Services\Images\WebPConverter;

use App\Services\AbstractService;
use Imagick;

class ImagickService extends AbstractService
{
    /**
     * Determine the required packages for this service.
     *
     * @return string|array<bool|string>
     */
    public static function requiredPackages(): array|string
    {
        return [
            extension_loaded('imagick'),
            extension_loaded('gd'),
            extension_loaded('exif'),
        ];
    }

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
