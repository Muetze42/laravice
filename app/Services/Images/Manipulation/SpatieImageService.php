<?php

namespace App\Services\Images\Manipulation;

use Spatie\Image\Enums\BorderType;
use Spatie\Image\Enums\CropPosition;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\FlipDirection;
use Spatie\Image\Enums\Orientation;
use Spatie\Image\Image;

class SpatieImageService
{
    /**
     * Determine the enums for methods.
     *
     * @return array<string|object>
     */
    protected array $enums = [
        'border' => BorderType::class,
        'crop' => CropPosition::class,
        'fit' => Fit::class,
        'flip' => FlipDirection::class,
        'orientation' => Orientation::class,
    ];

    protected string $method;

    /**
     * @throws \Spatie\Image\Exceptions\CouldNotLoadImage
     */
    public function __construct(string $path, array $actions)
    {
        $image = Image::load($path);

        // Todo: Error Handle
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
