<?php

namespace App\Services\Images\Manipulation;

use App\Services\AbstractService;
use Spatie\Image\Enums\CropPosition;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\FlipDirection;
use Spatie\Image\Enums\Orientation;
use Spatie\Image\Image;

class SpatieImageService extends AbstractService
{
    /**
     * Determine the enums for methods.
     *
     * @return array<string|object>
     */
    protected array $enums = [
        'crop' => CropPosition::class,
        'fit' => Fit::class,
        'flip' => FlipDirection::class,
        'orientation' => Orientation::class,
    ];

    protected string $method;

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
            $this->method = $method;
            $arguments = explode(',', $arguments);
            $arguments = array_map([$this, 'cast'], $arguments);
            $image = call_user_func_array([$image, $method], $arguments);
        }

        $image->save();
    }

    /**
     * Cast an arguments to an Enum case.
     */
    protected function cast(string $value): mixed
    {
        $value = trim($value);

        if (isset($this->enums[$this->method])) {
            /* @var CropPosition|Fit|FlipDirection|Orientation $enum */
            $enum = $this->enums[$this->method];
            $cases = $enum::cases();
            foreach ($cases as $case) {
                if ($case->value == $value) {
                    return $case;
                }
            }
        }

        return $value;
    }
}
