<?php

namespace App\Services\Images\Manipulation;

use App\Services\AbstractService;
use Spatie\Image\Image;

class SpatieImageService extends AbstractService
{
    /**
     * Determine the required packages for this service.
     *
     * @return string|array<bool|string>
     */
    public static function requiredPackages(): array|string
    {
        return [
            '@imgly/background-removal-node',
            extension_loaded('imagick'),
        ];
    }
    /**
     * @throws \Spatie\Image\Exceptions\CouldNotLoadImage
     */
    public function __construct(string $path, array $actions)
    {
        $image = Image::load($path);

        foreach ($actions as $method => $arguments) {
            $arguments = explode(',', $arguments);
            $arguments = array_map('trim', $arguments);
            $image = call_user_func([$image, $method], $arguments);
        }

        $image->save();
    }
}
